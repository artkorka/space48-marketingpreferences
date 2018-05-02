define(
    [
        'ko',
        'uiComponent',
        'newsletterSubmit',
        'jquery'
    ],
    function (ko, Component, Newsletter, $) {
        'use strict';

        return Component.extend({
            defaults: {
                template: 'CoxAndCox_MarketingPreferences/checkout/newsletter-signup'
            },

            thirdParty: ko.observable(false),
            postalMailings: ko.observable(false),
            newsletterSignup: ko.observable(false),

            /*check: function () {
                this.newsletterChecked(true);
                Newsletter.newsletterUpdate(true);
            },

            uncheck: function () {
                this.newsletterChecked(false);
                Newsletter.newsletterUpdate(false);
            },*/

            thirdPartyUpdate: function () {
                Newsletter.thirdPartyUpdate(this.thirdParty());
                return true;
            },

            postalMailingsUpdate: function () {
                Newsletter.postalMailingsUpdate(this.postalMailings());
                return true;
            },

            newsletterUpdate: function () {
                Newsletter.newsletterUpdate(this.newsletterSignup());
                return true;
            },

            thirdPartyLoad: function (target, viewModel) {
                var self = this;
                $.ajax({
                    url: Newsletter.url,
                    data: {
                        type_mailing: 'thirdParty'
                    },
                    success : function(data){

                        var nameField = $(target).attr('name');


                        if (data['third_party'] == 1) {
                            $("input[name="+nameField+"]").prop('checked',true);
                        } else {
                            $("input[name="+nameField+"]").prop('checked', false);
                        }

                    },
                    type: 'POST'
                });
            },

            postalMailingsLoad: function (target, viewModel) {
                var self = this;
                $.ajax({
                    url: Newsletter.url,
                    data: {
                        type_mailing: 'postalMailings'
                    },
                    success : function(data){
                        var nameField = $(target).attr('name');

                        if (data['postal_mailings'] == 1) {
                            $("input[name="+nameField+"]").prop('checked',true);
                        } else {
                            $("input[name="+nameField+"]").prop('checked', false);
                        }

                    },
                    type: 'POST'
                });
            },

            newsletterSignupLoad: function (target, viewModel) {
                var self = this;
                $.ajax({
                    url: Newsletter.url,
                    data: {
                        type_mailing: 'newsletterSignup'
                    },
                    success : function(data){
                        var nameField = $(target).attr('name');

                        if (data['newsletter_signup'] == 1) {
                            $("input[name="+nameField+"]").prop('checked',true);
                        } else {
                            $("input[name="+nameField+"]").prop('checked', false);
                        }

                    },
                    type: 'POST'
                });
            }

        });
    }
);
