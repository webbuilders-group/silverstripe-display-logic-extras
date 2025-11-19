import ReactDOM from 'react-dom';

(function($) {
    $.entwine('ss', function($) {
        $('.js-injector-boot div.uploadfield.display-logic-dispatcher input.entwine-uploadfield').entwine({
            refresh() {
                const props = this.getAttributes();
                const form = $(this).closest('form');
                const master = $(this).closest('.display-logic-dispatcher');
                const onChange = () => {
                    // Trigger change detection (see jquery.changetracker.js)
                    setTimeout(() => {
                        form.trigger('change');

                        master.notify();
                    }, 0);
                };

                const UploadField = this.getComponent();

                // TODO: rework entwine so that react has control of holder
                ReactDOM.render(
                    <UploadField
                        {...props}
                        onChange={onChange}
                        noHolder
                    />,
                    this.getContainer(),
                    () => {
                        setTimeout(() => {
                            master.notify();
                        }, 0);
                    }
                );
            }
        });

        $('div.uploadfield.display-logic-dispatcher').entwine({
            evaluateHasUpload() {
                return (this.find('.uploadfield-item:not(.uploadfield-item--error)').length > 0);
            },

            evaluateHasUploadedAtLeast(num) {
                return (this.find('.uploadfield-item:not(.uploadfield-item--error)').length >= num);
            },

            evaluateHasUploadedLessThan(num) {
                return (this.find('.uploadfield-item:not(.uploadfield-item--error)').length <= num);
            }
        });

        $('.js-injector-boot div.field.link.display-logic-dispatcher .entwine-linkfield, .js-injector-boot div.field.multilink.display-logic-dispatcher .entwine-linkfield').entwine({
            refresh() {
                const props = this.getProps();
                const form = $(this).closest('form');
                const master = $(this).closest('.display-logic-dispatcher');
                const originalOnChange = props.onChange;
                const onChange = (value) => {
                    if (originalOnChange) {
                        originalOnChange(value);
                    }
                    setTimeout(() => {
                        master.notify();
                    }, 0);
                };

                // Set the value attribute specifically with a "string array" when using a MultiLinkField
                // This is done to ensure the change-tracker works as expected otherwise the intial form state
                // will not match the component after it's refreshed, which happens when it's mounted
                let value = props.value;
                if (value && value.constructor === Array) {
                    value = '[' + value.join(',') + ']';
                }

                this.getInputField().val(value);

                const ReactField = this.getComponent();
                const Root = this.getRoot();

                Root.render(
                    <ReactField
                        {...props}
                        onChange={onChange}
                        noHolder
                    />
                );
            },
        });

        $('div.field.link.display-logic-dispatcher .link-field__container, div.field.multilink.display-logic-dispatcher .link-field__container').entwine({
            onmatch() {
                this._super();

                const master = $(this).closest('.display-logic-dispatcher');
                master.notify();
            },
        });

        $('div.field.link.display-logic-dispatcher, div.field.multilink.display-logic-dispatcher').entwine({
            evaluateHasLink() {
                const input = this.find('input.link, input.multilink');
                if (this.hasClass('multilink')) {
                    return (input.val() != '' && input.val() != '[]');
                }

                return (parseInt(input.val()) > 0);
            },
        });

        $('div.display-logic, div.display-logic-dispatcher').entwine({
            evaluateChanged() {
                return this.getFormField().hasClass('changed');
            },
        });
    });
})(jQuery);
