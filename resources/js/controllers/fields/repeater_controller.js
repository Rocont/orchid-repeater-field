import Sortable from 'sortablejs';
import axios from 'axios';
import * as Sqrl from 'squirrelly';
import ApplicationController from '~orchid/js/controllers/application_controller';

export default class extends ApplicationController {
    static targets = [
        'blocks',
        'content',
        'repeaterBlockCount',
        'addBlockButton',
        'repeaterField',
    ];

    template;

    options = {
        required: false,
        min: null,
        max: null,
        collapse: false,
    };

    sortableInstance = null;

    inputs = null;

    connect() {
        if (document.documentElement.hasAttribute('data-turbolinks-preview')
            || document.body.classList.contains('gu-unselectable')) {
            return;
        }

        this.options = Object.assign(
            this.options,
            JSON.parse(this.data.get('options')),
        );

        this.prepareTemplate();
        this.fetchFields();
        this.initDragDrop();
    }

    compileTemplate(html) {
        const config = Sqrl.defaultConfig;
        config.autoEscape = false;
        return Sqrl.compile(html, config);
    }

    renderBlock(content, index) {
        return this.template({
            name: this.blocksTarget.dataset.containerKey,
            content,
            block_key: index,
            block_count: `${this.options.title} ${index + 1}`,
        });
    }

    prepareTemplate() {
        const templateElement = document.getElementById(this.data.get('template'));

        if (templateElement) {
            this.template = this.compileTemplate(templateElement.innerHTML);
        }

        return this;
    }

    fetchFields() {
        const fieldName = this.repeaterFieldTarget.name;
        const repeaterData = this.getRepeaterData();
        const values = JSON.parse(this.data.get('value'));

        this.contentTarget.classList.add('loading');

        axios.post(this.data.get('url'), {
            values,
            repeater_name: fieldName,
            layout: this.data.get('layout'),
            repeater_data: repeaterData,
        }).then((response) => {
            if (!this.template && response.data.template) {
                const element = document.createElement('template');
                element.innerHTML = response.data.template.trim();
                this.template = this.compileTemplate(element.content.firstChild.innerHTML);
            }

            if (!this.template) {
                this.alert(
                    'Unexpected error',
                    `Error fetching repeater field template for ${this.options.title} (${this.options.name}).`,
                    'danger',
                );
                return;
            }

            if (response.data.fields) {
                response.data.fields.forEach((content, index) => {
                    if (this.options.max === null || index < this.options.max) {
                        this.blocksTarget.insertAdjacentHTML('beforeend', this.renderBlock(content, index));
                    }
                });
            }

            this.contentTarget.classList.remove('loading');
            this.initMinRequiredBlock();
            this.checkEmpty();
        });
    }

    initMinRequiredBlock() {
        if (this.options.required !== true && !this.options.min) {
            return;
        }

        const blocksCount = this.blocksTarget.querySelectorAll(
            ':scope > .repeater-item',
        ).length;

        if (!blocksCount && this.options.required === true && this.options.min === null) {
            this.options.min = 1;
        }

        if (this.options.min !== null && this.options.min > blocksCount) {
            const click = new CustomEvent('click', {
                detail: {
                    blocksNum: this.options.min - blocksCount,
                },
            });

            this.addBlockButtonTarget.dispatchEvent(click);
        }
    }

    initDragDrop() {
        this.sortableInstance = Sortable.create(this.blocksTarget, {
            handle: '.card-handle',
            animation: 150,
            onEnd: () => {
                this.sort();
                this.initTiny();
            },
        });

        return this;
    }

    checkEmpty() {
        this.contentTarget.classList.toggle(
            'empty',
            this.blocksTarget.querySelectorAll(':scope > .repeater-item').length === 0,
        );

        return this;
    }

    collapse(event) {
        const card = event.currentTarget.closest('.repeater-item');

        event.currentTarget.querySelector('.transition').classList.toggle('collapse-action');
        card.querySelector('.card-body').classList.toggle('collapse');
    }

    addNewBlock(event) {
        this.addBlock(null, event);

        return this;
    }

    addBlockAfter(event) {
        const currentBlock = event.currentTarget.closest('.repeater-item');
        this.addBlock(currentBlock, event);

        return this;
    }

    addBlock(currentBlock, event) {
        if (!this.template) {
            this.alert('Error', 'No template is defined.', 'danger');
            return;
        }

        const blocksCount = this.blocksTarget.querySelectorAll(
            ':scope > .repeater-item',
        ).length;
        const num = event?.detail?.blocksNum || 1;
        const repeaterData = this.getRepeaterData();

        if (this.options.max && blocksCount >= this.options.max) {
            this.alert(
                this.data.get('error-title'),
                this.data.get('max-error-message'),
            );
            return;
        }

        axios.post(this.data.get('url'), {
            layout: this.data.get('layout'),
            repeater_name: this.repeaterFieldTarget.name,
            blocks: blocksCount,
            num,
            repeater_data: repeaterData,
        }).then((r) => {
            if (r.data.fields) {
                r.data.fields.forEach((content, index) => {
                    const html = this.renderBlock(content, index);

                    if (currentBlock != null) {
                        currentBlock.insertAdjacentHTML('afterend', html);
                    } else {
                        this.blocksTarget.insertAdjacentHTML('beforeend', html);
                    }
                });
            }

            this.sort();
            this.checkEmpty();
        });
    }

    deleteBlock(event) {
        if (!window.confirm(this.data.get('confirm-delete-message'))) {
            return;
        }

        const blocksCount = this.blocksTarget.querySelectorAll(
            ':scope > .repeater-item',
        ).length;

        if (this.options.min && blocksCount <= this.options.min) {
            this.alert(
                this.data.get('error-title'),
                this.data.get('min-error-message'),
            );
            return;
        }

        event.currentTarget.closest('.repeater-item').remove();

        this.sort().checkEmpty();
        setTimeout(() => {
            this.initTiny();
            this.sort().checkEmpty();
        }, 200);
    }

    sort() {
        const blocks = this.blocksTarget.querySelectorAll(
            ':scope > .repeater-item',
        );
        blocks.forEach((block, currentKey) => {
            block.dataset.sort = currentKey;
            const fields = block.querySelectorAll('[data-repeater-name-key]');
            if (!fields.length) {
                return;
            }

            fields.forEach((field) => {
                const { repeaterNameKey } = field.dataset;
                let originalName = `[${repeaterNameKey.replace('.', '][')}]`;

                if (repeaterNameKey.endsWith('.')) {
                    originalName += '[]';
                }

                const inputs = field.querySelectorAll('input[type="hidden"]');
                if (inputs.length) {
                    inputs.forEach((input) => {
                        const inputOriginalName = `${originalName}[]`;
                        const resultInputName = `${input.closest('.repeaters_container').dataset.containerKey}[${input.closest('.repeater-item').dataset.sort}]${inputOriginalName}`;
                        input.setAttribute('name', resultInputName);
                    });
                }

                const resultName = `${field.closest('.repeaters_container').dataset.containerKey}[${field.closest('.repeater-item').dataset.sort}]${originalName}`;

                if (field.hasAttribute('data-upload-name')) {
                    field.setAttribute('data-upload-name', resultName);
                }
                field.setAttribute('name', resultName);
            });
        });

        if (this.hasRepeaterBlockCountTarget) {
            this.repeaterBlockCountTargets.forEach((content, index) => {
                content.innerHTML = `${this.options.title} ${index + 1}`;
            });
        }

        return this;
    }

    getRepeaterData() {
        return this.data.get('ajax-data')
            ? JSON.parse(this.data.get('ajax-data'))
            : null;
    }

    initTiny() {
        document.querySelectorAll('.tinymce').forEach((element) => {
            tinymce.init({
                selector: `#${element.id}`,
                language: 'ru',
                plugins: 'preview importcss searchreplace autolink autosave save directionality code visualblocks visualchars fullscreen image link media codesample table charmap pagebreak nonbreaking anchor insertdatetime advlist lists wordcount help charmap quickbars emoticons',
                toolbar: 'undo redo bold italic underline strikethrough fontfamily fontsize blocks alignleft aligncenter alignright alignjustify outdent indent  numlist bullist forecolor backcolor removeformat pagebreak charmap emoticons fullscreen code preview print insertfile image media link anchor codesample ltr rtl',
                menubar: false,
                table_header_type: 'section',
                images_upload_handler: this.imageUploadHandler,
            });
        });
    }

    imageUploadHandler = (blobInfo, progress) => {
        const csrfToken = document.head.querySelector('meta[name="csrf_token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('_token', csrfToken);
        formData.append('file', blobInfo.blob(), blobInfo.filename());

        return axios.post(this.prefix('/systems/files'), formData, {
            onUploadProgress: (e) => {
                progress(e.loaded / e.total * 100);
            },
        }).then((response) => {
            if (!response.data?.relativeUrl) {
                return Promise.reject('Invalid response');
            }
            return response.data.relativeUrl;
        }).catch((error) => {
            if (error.response?.status === 403) {
                return Promise.reject({ message: 'HTTP Error: 403', remove: true });
            }
            return Promise.reject(`HTTP Error: ${error.response?.status || error.message}`);
        });
    };
}
