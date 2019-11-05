define([
    'jquery',
    'Magento_Ui/js/form/element/abstract',
	'Magento_Ui/js/modal/modal'
], function($,Default,modal){

    return Default.extend({
        initialize: function (){
            this._super();
            $('[data-role="spinner"]').remove(); /* this shit sometimes disappears, sometimes it doesn't */
        },
        callAjax: function(){
            window.open(window.emailTesterSubmitUrl.replace('hestaworksOrderId',this.value()));
			/*var value = this.value();
            $.ajax({
                url: window.emailTesterSubmitUrl,
                method: 'POST',
				showLoader: true,
                data: {
                    id: this.value(),
					form_key: window.FORM_KEY
                },
                success: function (data) {
					$("#popup-modal").html(data);
                    var options = {
						type: 'popup',
						responsive: true,
						innerScroll: false,
						title: 'Order #'+value,
						buttons: [{
							text: $.mage.__('Close'),
							class: '',
							click: function () {
								this.closeModal();
							}
						}]
					};

					var popup = modal(options, $('#popup-modal'));

					$('#popup-modal').modal('openModal');
                }
            });*/
        }
    });
});
