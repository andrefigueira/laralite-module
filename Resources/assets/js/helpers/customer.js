import _ from "lodash";

export default {
    isSubscribed(customer) {
        if (typeof customer.subscriptions === "undefined") {
            throw new Error('customer.subscriptions must be set to use `isSubscribed` customer helper.');
        }
        let subscribed = _.find(customer.subscriptions || [], {'status': 'ACTIVE'});
        return typeof subscribed !== "undefined";
    }
}
