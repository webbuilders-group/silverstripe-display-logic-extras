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

        $('div.display-logic, div.display-logic-dispatcher').entwine({
            evaluateChanged() {
                return this.getFormField().hasClass('changed');
            },
        });
    });
})(jQuery);
