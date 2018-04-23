define([
    'jquery'
], function ($) {
    'use strict'
    return {
        subscribedOk: 1,

        thirdPartyOk: 1,

        postalMailingsOk: 1,

        newsletterSubmit: function (config, node) {
            this.url = config.url;
            //$(document).ready($.proxy(this.submit, this));
        },

        newsletterUpdate: function (subscribedOk) {
            this.subscribedOk = subscribedOk ? 1 : 0;
            this.submit('newsletter');
        },

        thirdPartyUpdate: function (thirdPartyOk) {
            this.thirdPartyOk = thirdPartyOk ?  1 : 0;
            this.submit('third_party');
        },

        postalMailingsUpdate: function (postalMailingsOk) {
            this.postalMailingsOk = postalMailingsOk ?  1 : 0;
            this.submit('postal_mailings');
        },



        submit: function (typeSubscribtion) {
            var self = this;
            var requestData;

            if (typeSubscribtion == 'newsletter'){
                requestData = {optIn_NewsletterMailings: self.subscribedOk};
            }

            if (typeSubscribtion == 'third_party'){
                requestData = {optIn_ThirdParty: self.thirdPartyOk};
            }

            if (typeSubscribtion == 'postal_mailings'){
                requestData = {optIn_PostalMailings: self.postalMailingsOk};
            }

            $.ajax({
                url: this.url,
                data: requestData,
                type: 'POST'
            });
        }
    }
});
