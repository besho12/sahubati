import store from "../../store";
import Errors from "../../Errors";
import CartHelpersMixin from "../../mixins/CartHelpersMixin";
import CartItemHelpersMixin from "../../mixins/CartItemHelpersMixin";

export default {
    mixins: [CartHelpersMixin, CartItemHelpersMixin],

    props: [
        "customerEmail",
        "customerPhone",
        "gateways",
        "defaultAddress",
        "addresses",
        "countries",
        "areas",
    ],

    data() {
        return {
            form: {
                customer_email: this.customerEmail,
                customer_phone: this.customerPhone,
                billing: {},
                shipping: {},
                billingAddressId: null,
                shippingAddressId: null,
                newBillingAddress: false,
                newShippingAddress: false,
                ship_to_a_different_address: false,
            },
            states: {
                billing: {},
                shipping: {},
            },
            controller: null,
            placingOrder: false,
            errors: new Errors(),
            authorizeNetToken: null,
            payFastFormFields: null,
        };
    },

    computed: {
        hasAddress() {
            return Object.keys(this.addresses).length !== 0;
        },

        firstCountry() {
            return Object.keys(this.countries)[0];
        },

        hasBillingStates() {
            return Object.keys(this.states.billing).length !== 0;
        },

        hasShippingStates() {
            return Object.keys(this.states.shipping).length !== 0;
        },

        hasNoPaymentMethod() {
            return Object.keys(this.gateways).length === 0;
        },

        firstPaymentMethod() {
            return Object.keys(this.gateways)[0];
        },

        shouldShowPaymentInstructions() {
            return ["bank_transfer", "check_payment"].includes(
                this.form.payment_method
            );
        },

        paymentInstructions() {
            if (this.shouldShowPaymentInstructions) {
                return this.gateways[this.form.payment_method].instructions;
            }
        },

        hasFreeShipping() {
            return this.cart.coupon?.free_shipping ?? false;
        },
    },

    watch: {
        "form.billing.city": function (newCity) {
            if (newCity) {
                this.addTaxes();
            }
        },

        "form.shipping.city": function (newCity) {
            if (newCity) {
                this.addTaxes();
            }
        },

        "form.billing.zip": function (newZip) {
            if (newZip) {
                this.addTaxes();
            }
        },

        "form.shipping.zip": function (newZip) {
            if (newZip) {
                this.addTaxes();
            }
        },

        "form.billing.state": function (newState) {
            if (newState) {
                this.addTaxes();
            }
        },

        "form.shipping.state": function (newState) {
            if (newState) {
                this.addTaxes();
            }
        },

        "form.ship_to_a_different_address": function (newValue) {
            if (newValue && this.form.shippingAddressId) {
                this.form.shipping =
                    this.addresses[this.form.shippingAddressId];
            } else {
                this.form.shipping = {};
                this.resetAddressErrors("shipping");
            }

            this.addTaxes();
        },

        "form.terms_and_conditions": function () {
            this.errors.clear("terms_and_conditions");
        },

        "form.payment_method": function (newPaymentMethod) {
            if (newPaymentMethod === "paypal") {
                this.$nextTick(this.renderPayPalButton);
            }
        },

        "form.billing.area": function (newArea) {
            if (newArea) {
                // store.state.cart.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.formatted = this.calculateShippingCost(this.form.billing.area);
            }
        },
    },

    created() {
        if (this.defaultAddress.address_id) {
            this.form.billingAddressId = this.defaultAddress.address_id;
            this.form.shippingAddressId = this.defaultAddress.address_id;

            this.mergeSavedBillingAddress();
            this.mergeSavedShippingAddress();
        }

        if (!this.hasAddress) {
            this.form.newBillingAddress = true;
            this.form.newShippingAddress = true;
        }

        this.$nextTick(() => {
            this.changePaymentMethod(this.firstPaymentMethod);

            if (store.state.cart.shippingMethodName) {
                this.changeShippingMethod(store.state.cart.shippingMethodName);
            } else {
                this.updateShippingMethod(this.firstShippingMethod);
            }
        });
    },

    methods: {
        changeBillingAddress(address) {
            if (
                this.form.newBillingAddress ||
                this.form.billingAddressId === address.id
            ) {
                return;
            }

            this.form.billingAddressId = address.id;

            this.mergeSavedBillingAddress();
        },

        addNewBillingAddress() {
            this.resetAddressErrors("billing");

            this.form.billing = {};
            this.form.newBillingAddress = !this.form.newBillingAddress;

            if (!this.form.newBillingAddress) {
                this.mergeSavedBillingAddress();
            }
        },

        changeShippingAddress(address) {
            if (
                this.form.newShippingAddress ||
                this.form.shippingAddressId === address.id
            ) {
                return;
            }

            this.form.shippingAddressId = address.id;

            this.mergeSavedShippingAddress();
        },

        addNewShippingAddress() {
            this.resetAddressErrors("shipping");

            this.form.shipping = {};
            this.form.newShippingAddress = !this.form.newShippingAddress;

            if (!this.form.newShippingAddress) {
                this.mergeSavedShippingAddress();
            }
        },

        // reset address errors based on address type
        resetAddressErrors(addressType) {
            Object.keys(this.errors.errors).map((key) => {
                key.indexOf(addressType) !== -1 && this.errors.clear(key);
            });
        },

        mergeSavedBillingAddress() {
            this.resetAddressErrors("billing");

            if (!this.form.newBillingAddress && this.form.billingAddressId) {
                this.form.billing = this.addresses[this.form.billingAddressId];
            }
        },

        mergeSavedShippingAddress() {
            this.resetAddressErrors("shipping");

            if (
                this.form.ship_to_a_different_address &&
                !this.form.newShippingAddress &&
                this.form.shippingAddressId
            ) {
                this.form.shipping =
                    this.addresses[this.form.shippingAddressId];
            }
        },

        changeBillingCity(city) {
            this.$set(this.form.billing, "city", city);
        },

        changeShippingCity(city) {
            this.$set(this.form.shipping, "city", city);
        },

        changeBillingZip(zip) {
            this.$set(this.form.billing, "zip", zip);
        },

        changeShippingZip(zip) {
            this.$set(this.form.shipping, "zip", zip);
        },

        changeBillingCountry(country) {
            this.$set(this.form.billing, "country", country);

            if (country === "") {
                this.form.billing.state = "";
                this.states.billing = {};

                return;
            }

            this.fetchStates(country, (response) => {
                this.$set(this.states, "billing", response.data);
                this.$set(this.form.billing, "state", "");
            });
        },

        changeBillingArea(area) {
            this.$set(this.form.billing, "area", area);
        },

        changeShippingCountry(country) {
            this.$set(this.form.shipping, "country", country);

            if (country === "") {
                this.form.shipping.state = "";
                this.states.shipping = {};

                return;
            }

            this.fetchStates(country, (response) => {
                this.$set(this.states, "shipping", response.data);
                this.$set(this.form.shipping, "state", "");
            });
        },

        changeShippingArea(area) {
            this.$set(this.form.shipping, "area", area);

            if (area === "") {
                this.form.shipping.state = "";
                this.states.shipping = {};

                return;
            }

            this.fetchStates(area, (response) => {
                this.$set(this.states, "shipping", response.data);
                this.$set(this.form.shipping, "state", "");
            });
        },

        fetchStates(country, callback) {
            axios
                .get(route("countries.states.index", { code: country }))
                .then(callback);
        },

        changeBillingState(state) {
            this.$set(this.form.billing, "state", state);
        },

        changeShippingState(state) {
            this.$set(this.form.shipping, "state", state);
        },

        changePaymentMethod(paymentMethod) {
            this.$set(this.form, "payment_method", paymentMethod);
        },

        changeShippingMethod(shippingMethodName) {
            this.$set(this.form, "shipping_method", shippingMethodName);
            // store.state.cart.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.formatted = this.calculateShippingCost(this.form.billing.area);
        },

        async updateShippingMethod(shippingMethodName) {
            if (!shippingMethodName) {
                return;
            }

            this.loadingOrderSummary = true;

            this.changeShippingMethod(shippingMethodName);

            try {
                const response = await axios.post(
                    route("cart.shipping_method.store"),
                    {
                        shipping_method: shippingMethodName,
                    }
                );

                response.data=await this.calculateShippingCost(this.form.billing.area,response.data); 
                store.updateCart(response.data);

                // This will now execute after the `await` call completes successfully
                // store.state.cart.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.formatted = this.calculateShippingCost(this.form.billing.area);

            } catch (error) {
                this.$notify(error.response.data.message);
            } finally {
                this.loadingOrderSummary = false;
            }

        },

        async addTaxes() {
            this.loadingOrderSummary = true;

            try {
                const response = await axios.post(
                    route("cart.taxes.store"),
                    this.form
                );                   
                response.data=await this.calculateShippingCost(this.form.billing.area,response.data);                
                store.updateCart(response.data);                
            } catch (error) {
                this.$notify(error.response.data.message);
            } finally {
                this.loadingOrderSummary = false;
            }
        },

        async calculateShippingCost(NewArea, CartData) {
            try {
                const response = await axios.post(
                    route("cart.shipping_method.getAreaCost", {
                        area: NewArea,
                    })
                );
        
                console.log(CartData);
                // Update CartData with response data
                CartData.availableShippingMethods.shipping_rate.cost.amount = response.data;
                CartData.availableShippingMethods.shipping_rate.cost.formatted = CartData.availableShippingMethods.shipping_rate.cost.currency + ' ' + response.data.toFixed(3);
                CartData.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.amount = response.data;
                CartData.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.formatted = CartData.availableShippingMethods.shipping_rate.cost.inCurrentCurrency.currency + ' ' + response.data.toFixed(3);
        
                if (CartData.shippingMethodName && CartData.shippingMethodName === "shipping_rate") {
                    CartData.shippingCost.amount = response.data;
                    CartData.shippingCost.formatted = CartData.shippingCost.currency + ' ' + response.data.toFixed(3);
                    CartData.shippingCost.inCurrentCurrency.amount = response.data;
                    CartData.shippingCost.inCurrentCurrency.formatted = CartData.shippingCost.inCurrentCurrency.currency + ' ' + response.data.toFixed(3);
        
                    CartData.total.amount = CartData.subTotal.amount + CartData.shippingCost.amount;
                    CartData.total.formatted = CartData.total.currency + ' ' + (CartData.subTotal.amount + CartData.shippingCost.amount).toFixed(3);
                    CartData.total.inCurrentCurrency.amount = CartData.subTotal.amount + CartData.shippingCost.amount;
                    CartData.total.inCurrentCurrency.formatted = CartData.total.inCurrentCurrency.currency + ' ' + (CartData.subTotal.amount + CartData.shippingCost.amount).toFixed(3);
                }
        
                return CartData; // Now you can return the updated CartData
            } catch (error) {
                console.error("Error calculating shipping cost:", error);
                throw error; // Optionally propagate the error
            }
        },

        updateCart(cartItem, qty) {
            this.loadingOrderSummary = true;

            if (this.controller) {
                this.controller.abort();
            }

            this.controller = new AbortController();

            axios
                .put(
                    route("cart.items.update", {
                        id: cartItem.id,
                    }),
                    {
                        qty: qty || 1,
                    },
                    {
                        signal: this.controller.signal,
                    }
                )
                .then((response) => {
                    store.updateCart(response.data);
                })
                .catch((error) => {
                    if (error.code !== "ERR_CANCELED") {
                        store.updateCart(error.response.data.cart);

                        this.$notify(error.response.data.message);
                    }
                })
                .finally(() => {
                    this.loadingOrderSummary = false;
                });
        },

        placeOrder() {
            if (!this.form.terms_and_conditions || this.placingOrder) {
                return;
            }

            this.placingOrder = true;

            axios
                .post(route("checkout.store"), {
                    ...this.form,
                    ship_to_a_different_address:
                        +this.form.ship_to_a_different_address,
                })
                .then(({ data }) => {
                    if (data.redirectUrl) {
                        window.location.href = data.redirectUrl;
                    } else if (this.form.payment_method === "paytm") {
                        this.confirmPaytmPayment(data);
                    } else if (this.form.payment_method === "paymob") {
                        this.confirmPaymobPayment(data);
                    } else if (this.form.payment_method === "razorpay") {
                        this.confirmRazorpayPayment(data);
                    } else if (this.form.payment_method === "paystack") {
                        this.confirmPaystackPayment(data);
                    } else if (this.form.payment_method === "authorizenet") {
                        this.confirmAuthorizeNetPayment(data);
                    } else if (this.form.payment_method === "flutterwave") {
                        this.confirmFlutterWavePayment(data);
                    } else if (this.form.payment_method === "mercadopago") {
                        this.confirmMercadoPagoPayment(data);
                    } else if (this.form.payment_method === "payfast") {
                        this.confirmPayFastPayment(data);
                    } else {
                        this.confirmOrder(
                            data.orderId,
                            this.form.payment_method
                        );
                    }
                })
                // .catch(({ response }) => {
                //     if (response.status === 422) {
                //         this.errors.record(response.data.errors);
                //     }

                //     this.$notify(response.data.message);

                //     this.placingOrder = false;
                // });
        },

        confirmOrder(orderId, paymentMethod, params = {}) {
            axios
                .get(
                    route("checkout.complete.store", {
                        orderId,
                        paymentMethod,
                        ...params,
                    })
                )
                .then(() => {
                    window.location.href = route("checkout.complete.show");
                })
                .catch((error) => {
                    this.placingOrder = false;
                    this.loadingOrderSummary = false;

                    this.deleteOrder(orderId);
                    this.$notify(error.response.data.message);
                });
        },

        deleteOrder(orderId) {
            if (!orderId) {
                return;
            }

            axios
                .get(route("checkout.payment_canceled.store", { orderId }))
                .then((response) => {
                    this.$notify(response.data.message);
                });
        },

        renderPayPalButton() {
            let vm = this;
            let response;

            window.paypal
                .Buttons({
                    async createOrder() {
                        try {
                            response = await axios.post(
                                route("checkout.create"),
                                vm.form
                            );

                            return response.data.resourceId;
                        } catch ({ response }) {
                            if (response.status === 422) {
                                vm.errors.record(response.data.errors);

                                return;
                            }

                            vm.$notify(response.data.message);
                        }
                    },
                    onApprove() {
                        vm.loadingOrderSummary = true;

                        vm.confirmOrder(
                            response.data.orderId,
                            "paypal",
                            response.data
                        );
                    },
                    onError() {
                        vm.deleteOrder(response.data.orderId);
                    },
                    onCancel() {
                        vm.deleteOrder(response.data.orderId);
                    },
                })
                .render("#paypal-button-container");
        },

        confirmPaytmPayment({ orderId, amount, txnToken }) {
            let config = {
                root: "",
                flow: "DEFAULT",
                data: {
                    orderId: orderId,
                    token: txnToken,
                    tokenType: "TXN_TOKEN",
                    amount: amount,
                },
                merchant: {
                    name: FleetCart.storeName,
                    redirect: false,
                },
                handler: {
                    transactionStatus: (response) => {
                        if (response.STATUS === "TXN_SUCCESS") {
                            this.confirmOrder(orderId, "paytm", response);
                        } else if (response.STATUS === "TXN_FAILURE") {
                            this.placingOrder = false;

                            this.deleteOrder(orderId);
                        }

                        window.Paytm.CheckoutJS.close();
                    },
                    notifyMerchant: (eventName) => {
                        if (eventName === "APP_CLOSED") {
                            this.placingOrder = false;

                            this.deleteOrder(orderId);
                        }
                    },
                },
            };

            window.Paytm.CheckoutJS.init(config)
                .then(() => {
                    window.Paytm.CheckoutJS.invoke();
                })
                .catch(() => {
                    this.deleteOrder(orderId);
                });
        },

        confirmPaymobPayment(data) {
            axios
            .post(
                route("paymob.paymob_create_order", {
                    order_id: data['order_id'],
                })
            )
            .then((data) => {
                window.open(data['data'], '_self');
            })
            .catch((error) => {
                // console.log('nooooooo');
                // this.placingOrder = false;
                // this.loadingOrderSummary = false;

                // this.deleteOrder(data['order_id']);
                // this.$notify(error.response.data.message);
            });
            
        },

        confirmRazorpayPayment(razorpayOrder) {
            this.placingOrder = false;

            let vm = this;

            new window.Razorpay({
                key: FleetCart.razorpayKeyId,
                name: FleetCart.storeName,
                description: `Payment for order #${razorpayOrder.receipt}`,
                image: FleetCart.storeLogo,
                order_id: razorpayOrder.id,
                handler(response) {
                    vm.placingOrder = true;

                    vm.confirmOrder(
                        razorpayOrder.receipt,
                        "razorpay",
                        response
                    );
                },
                modal: {
                    ondismiss() {
                        vm.deleteOrder(razorpayOrder.receipt);
                    },
                },
                prefill: {
                    name: `${vm.form.billing.first_name} ${vm.form.billing.last_name}`,
                    email: vm.form.customer_email,
                    contact: vm.form.customer_phone,
                },
            }).open();
        },

        confirmPaystackPayment({
            key,
            email,
            amount,
            ref,
            currency,
            order_id,
        }) {
            let vm = this;

            PaystackPop.setup({
                key,
                email,
                amount,
                ref,
                currency,
                onClose() {
                    vm.placingOrder = false;

                    vm.deleteOrder(order_id);
                },
                callback(response) {
                    vm.placingOrder = false;

                    vm.confirmOrder(order_id, "paystack", response);
                },
                onBankTransferConfirmationPending(response) {
                    vm.placingOrder = false;

                    vm.confirmOrder(order_id, "paystack", response);
                },
            }).openIframe();
        },

        confirmAuthorizeNetPayment({ token }) {
            this.authorizeNetToken = token;

            this.$nextTick(() => {
                this.$refs.authorizeNetForm.submit();

                this.authorizeNetToken = null;
            });
        },

        confirmFlutterWavePayment({
            public_key,
            tx_ref,
            order_id,
            amount,
            currency,
            payment_options,
            redirect_url,
        }) {
            let vm = this;

            FlutterwaveCheckout({
                public_key,
                tx_ref,
                amount,
                currency,
                payment_options: payment_options.join(", "),
                redirect_url,
                customer: {
                    email: this.form.customer_email,
                    phone_number: this.form.customer_phone,
                    name: this.form.billing.full_name,
                },
                customizations: {
                    title: FleetCart.storeName,
                    logo: FleetCart.storeLogo,
                },
                onclose(incomplete) {
                    vm.placingOrder = false;

                    if (incomplete) {
                        vm.deleteOrder(order_id);
                    }
                },
            });
        },

        confirmMercadoPagoPayment(mercadoPagoOrder) {
            this.placingOrder = false;

            const SUPPORTED_LOCALES = {
                en_US: "en-US",
                es_AR: "es-AR",
                es_CL: "es-CL",
                es_CO: "es-CO",
                es_MX: "es-MX",
                es_VE: "es-VE",
                es_UY: "es-UY",
                es_PE: "es-PE",
                pt_BR: "pt-BR",
            };

            const mercadoPago = new MercadoPago(mercadoPagoOrder.publicKey, {
                locale:
                    SUPPORTED_LOCALES[mercadoPagoOrder.currentLocale] ||
                    "en-US",
            });

            mercadoPago.checkout({
                preference: {
                    id: mercadoPagoOrder.preferenceId,
                },
                autoOpen: true,
            });
        },

        confirmPayFastPayment(payFastOrder) {
            this.payFastFormFields = payFastOrder.formFields;

            this.$nextTick(() => {
                this.$refs.payFastForm.submit();
            });
        },
    },
};
