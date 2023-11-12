const get_token = () => {
   return window.localStorage.getItem('token');
};

const set_token = (token) => {
   window.localStorage.setItem('token', token);
};

const logout = () => {
   window.localStorage.removeItem('token');
}

export {
   get_token,
   set_token,
   logout
}