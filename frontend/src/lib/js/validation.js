const add_error = (item) => {
    item.classList.add("dark:text-red-500");
    item.classList.add("text-red-500");
    item.classList.add("border-red-500");
};

const remove_error = (item) => {
    item.classList.remove("dark:text-red-500");
    item.classList.remove("text-red-500");
    item.classList.remove("border-red-500");
};

const validate_email = (email) => {
    var validRegex =
        /^[a-zA-Z0-9.!#$%&'*+/=?^_`{|}~-]+@[a-zA-Z0-9-]+(?:\.[a-zA-Z0-9-]+)*$/;
    return email.match(validRegex)
}

const validate_username = (username) => {
    var validRegex = /^[a-zA-Z0-9]{5,30}$/;
    return username.match(validRegex)
}

const validate_password = (password) => {
    return password.match(/[a-z]/) && password.match(/[A-Z]/) && password.match(/\d/) && password.match(/\W|_/) && password.length > 8
}

const validate_field = (type, field, error_box) => {
    let validation_function = null;
    if (type === "email") {
        validation_function = validate_email;
    }
    else if (type === "username") {
        validation_function = validate_username;
    }
    else if (type === "password") {
        validation_function = validate_password;
    }
    else if (typeof type === "function") {
        validation_function = type;
    }
    else {
        throw new Error("Invalid validation type");
    }

    if (!validation_function(field.value)) {
        add_error(field);
        error_box.classList.remove("hidden");
        throw new Error("Invalid field");
    }
    remove_error(field);
    error_box.classList.add("hidden");

    return field.value;
}


export {
    validate_field
}