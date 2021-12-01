import * as moment from "moment";

export const isValidPostcode = (value) => {
    if (value === undefined) return false;

    let postcode = value.replace(/\s/g, "");
    let regex = /^[A-Z]{1,2}[0-9]{1,2} ?[0-9][A-Z]{2}$/i;

    return regex.test(postcode);
}

export const isUniqueEmail = (value) => {
    if (value === '') {
        return true;
    }

    if (value === undefined) {
        return false;
    }

    if (!value.includes('@')) {
        return true;
    }

    return new Promise((resolve, reject) => {
        setTimeout(function () {
            console.log('Running window.Cradle check');
            if (window.Cradle === undefined) {
                console.log('window.Cadle is still undefined');
            } else {
                console.log('No longer undefined', window.Cradle);

                if (window.Cradle.auth.authed_user.email !== undefined && window.Cradle.auth.authed_user.email === value) {
                    console.log('window.Cradle.auth matches value', value);

                    resolve(true);
                }
            }

            axios.post('/api/validate-email', {
                email: value
            }).then(response => {
                resolve(response.data.success);
            }).catch(error => {
                resolve(false);
            });
        }, 500);
    });
};

export const isValidDate = (format) => (value) => {
    let dateRegex = /^\d{2}\/\d{2}\/\d{4}$/;

    return moment(value, format).isValid() && dateRegex.test(value);
};

export const isValidNiNumber = (value) => {
    let regex = /^[abceghj-prstw-z][abceghj-nprstw-z]\d{6}[abcd]$/i;

    return regex.test(value);
};

export const isValidMobileNumber = (value) => {
    let regex = /^(?:\W*\d){11}\W*$/;

    return regex.test
};

export const isValidEmail = (email) => {
    const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

export const secretKey = (value) => {
    let regex = /(.{10})(.*)(?=.2)/;

    return regex.test(value);
};
