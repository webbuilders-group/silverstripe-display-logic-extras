(function($) {
    $.entwine('ss', function($) {
        $('div.ss-upload.display-logic-master').entwine({
            onmatch: function() {
                this._super();
                
                var self=this;
                self.bind('fileuploadfinished', function() {
                    self.notify();
                });
                
                self.bind('fileuploaddestroyed', function() {
                    self.notify();
                });
                
                self.fileupload('option', 'destroy', function(e, data) {
                    var that=$(this).data('fileupload');
                    if(data.url) {
                        $.ajax(data);
                    }
                    
                    that._adjustMaxNumberOfFiles(1);
                    that._transitionCallback(
                        data.context.removeClass('in'),
                        function (node) {
                            node.remove();
                            
                            that._trigger('destroyed', e, data);
                        }
                    );
                });
            },
            
            attachFiles: function(ids, uploadedFileId) {
                this._super(ids, uploadedFileId);
                
                this.notify();
            }
        });
    });
    
    $('div.ss-upload.display-logic-master').entwine({
        evaluateHasUpload: function() {
            return this.find('.template-download:not(.ui-state-error)').length>0;
        },
        
        hasUploadedAtLeast: function(num) {          
            return this.find('.template-download:not(.ui-state-error)').length>=num;
        },
        
        hasUploadedLessThan: function(num) {
            return this.find('.template-download:not(.ui-state-error)').length<=num;
        }
    });
})(jQuery);