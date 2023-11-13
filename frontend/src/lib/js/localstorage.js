import { writable } from "svelte/store";
import * as yup from 'yup';

const get_token = () => {
   return window.localStorage.getItem('token');
};

const set_token = (token) => {
   window.localStorage.setItem('token', token);
};

const logout = () => {
   empty_cart();
   window.localStorage.removeItem('token');
}

const schema = yup.array().of(yup.object().shape({
   id: yup.number().positive().integer().required("book_id is required"),
   quantity: yup.number().positive().integer().required("quantity is required"),
}));

const read_cart = () => {
   let cart = window.localStorage.getItem('cart');
   if (!cart) {
      return [];
   }

   try {
      cart = JSON.parse(cart)
      schema.validateSync(cart);
      return cart;
   }
   catch (err) {
      window.localStorage.removeItem('cart');
      return [];
   }
}

const cart = writable(read_cart());
cart.subscribe((x) => {
   window.localStorage.setItem('cart', JSON.stringify(x));
});

const get_cart = () => {
   return cart;
}

const set_cart = (_cart) => {
   cart = _cart;
}

const delete_cart = (id) => {
   let cart = get_cart();

   cart.update((x) => {
      return x.filter(x => {
         return x.id !== id;
      })
   });
}

const add_cart = (book) => {
   let cart = get_cart();
   cart.update((x) => {
      let found = false;
      // check if book is already in cart
      x.forEach((item) => {
         if (item.id === book.id) {
            item.quantity += 1;
            found = true;
         }
      });

      if (!found) {
         x.push({
            ...book,
            quantity: 1,
         });
      }

      return x;
   });
}

const empty_cart = () => {
   cart.set([]);
}

export {
   get_token,
   set_token,
   logout,

   get_cart,
   delete_cart,
   add_cart,
   empty_cart,
}