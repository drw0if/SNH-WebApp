const apiURL = 'http://localhost:3000/api/v1';

const get = async (url, headers = {}) => {
    const req = await fetch(`${apiURL}${url}`, {
        headers: {
            ...headers
        }
    })

    return req;
}

const post = async (url, data, headers = {}) => {
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