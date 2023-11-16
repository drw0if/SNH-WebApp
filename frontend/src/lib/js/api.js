import { get_token } from './localstorage.js';
const apiURL = window.origin + '/api/v1';

const get = async (url, authenticated) => {
    let headers = {};
    if (authenticated) {
        headers = {
            'Authorization': `${get_token()}`
        }
    }

    const req = await fetch(`${apiURL}${url}`, {
        headers: {
            ...headers
        }
    })

    return req;
}

const post = async (url, data, authenticated) => {
    let headers = {};
    if (authenticated) {
        headers = {
            'Authorization': `${get_token()}`
        }
    }
    const req = await fetch(`${apiURL}${url}`, {
        method: 'POST',
        headers: {
            ...headers,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(data),
    })

    return req;
}

export {
    post,
    get,
}