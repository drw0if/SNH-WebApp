<script>
    import { get_token, get_cart, empty_cart } from "$lib/js/localstorage.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { post } from "$lib/js/api.js";
    import { validate_field } from "$lib/js/validation.js";
    import { onMount } from "svelte";
    import { get } from "svelte/store";

    let stage = 1;

    let carrellazzo = get_cart();

    $: total =
        $carrellazzo
            .map((x) => {
                return x.price * x.quantity;
            })
            .reduce((x, y) => {
                return x + y;
            }, 0) / 100;

    let credit_card_number;
    let credit_card_number_input;
    let credit_card_number_error_box;

    let credit_card_expiration;
    let credit_card_expiration_input;
    let credit_card_expiration_error_box;

    let credit_card_cvv;
    let credit_card_cvv_input;
    let credit_card_cvv_error_box;

    let shipping_address;
    let shipping_address_input;
    let shipping_address_error_box;

    let shipping_city;
    let shipping_city_input;
    let shipping_city_error_box;

    let shipping_state;
    let shipping_state_input;
    let shipping_state_error_box;

    onMount(() => {
        const token = get_token();
        if (token === null) document.location = "/login";
    });

    const fire_form = async () => {
        if (stage === 1) {
            // Validate credit_card_number
            validate_field(
                (x) => {
                    let regex = /^[0-9]{4} [0-9]{4} [0-9]{4} [0-9]{4}$/;
                    return regex.test(x);
                },
                credit_card_number_input,
                credit_card_number_error_box,
            );

            validate_field(
                (x) => {
                    let regex = /^(0[1-9]|1[0-2])\/?([0-9]{4}|[0-9]{2})$/;
                    return regex.test(x);
                },
                credit_card_expiration_input,
                credit_card_expiration_error_box,
            );

            validate_field(
                (x) => {
                    let regex = /^[0-9]{3}$/;
                    return regex.test(x);
                },
                credit_card_cvv_input,
                credit_card_cvv_error_box,
            );

            stage = 2;
        } else if (stage === 2) {
            // Validate shipping road
            validate_field(
                (x) => {
                    return x.length > 0;
                },
                shipping_address_input,
                shipping_address_error_box,
            );
            validate_field(
                (x) => {
                    return x.length > 0;
                },
                shipping_city_input,
                shipping_city_error_box,
            );
            validate_field(
                (x) => {
                    return x.length > 0;
                },
                shipping_state_input,
                shipping_state_error_box,
            );

            stage = 3;
        } else {
            stage = 0;
            let cart = get(get_cart());

            cart = cart.map((x) => {
                return {
                    book_id: x.id,
                    quantity: x.quantity,
                };
            });

            const req = await post(
                "/order.php",
                {
                    credit_card_number: credit_card_number.replaceAll(" ", ""),
                    credit_card_expiration_date: credit_card_expiration,
                    credit_card_cvv,
                    cart,
                    shipping_address,
                    shipping_city,
                    shipping_state,
                },
                true,
            );

            if (req.status === 200) {
                empty_cart();
                document.location = "/bookshelf";
            } else {
                const res = await req.json();
                toast.set({ message: res.error });
            }
        }
    };
</script>

<svelte:head>
    <title>just b00k | Checkout</title>
    <meta name="description" content="just b00k checkout page" />
</svelte:head>

<div
    class="flex flex-col items-center justify-center px-6 py-8 mx-auto my-auto lg:py-0"
>
    <ol
        class="items-center w-full space-y-4 sm:flex sm:space-x-8 sm:space-y-0 rtl:space-x-reverse mb-10"
    >
        <li>
            <button
                on:click={() => (stage = 1)}
                disabled={stage < 1}
                class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse"
            >
                <span
                    class="flex items-center justify-center w-8 h-8 border border-blue-600 rounded-full shrink-0 dark:border-blue-500"
                >
                    1
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Credit card Info</h3>
                    <p class="text-sm">Fill in the card data</p>
                </span>
            </button>
        </li>
        <li>
            <button
                on:click={() => (stage = 2)}
                class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse disabled:opacity-20"
                disabled={stage < 2}
            >
                <span
                    class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400"
                >
                    2
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Shipping info</h3>
                    <p class="text-sm">Where will the books be?</p>
                </span>
            </button>
        </li>
        <li>
            <button
                class="flex items-center text-blue-600 dark:text-blue-500 space-x-2.5 rtl:space-x-reverse disabled:opacity-20"
                disabled={stage < 3}
            >
                <span
                    class="flex items-center justify-center w-8 h-8 border border-gray-500 rounded-full shrink-0 dark:border-gray-400"
                >
                    3
                </span>
                <span>
                    <h3 class="font-medium leading-tight">Checkout</h3>
                    <p class="text-sm">Buy right now!</p>
                </span>
            </button>
        </li>
    </ol>

    <div
        class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
    >
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1
                class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
            >
                {#if stage !== 3}
                    Fill the form to checkout
                {:else}
                    Complete the order
                {/if}
            </h1>
            <form class="space-y-4 md:space-y-6" action="#">
                {#if stage == 1}
                    <div>
                        <label
                            for="credit_card_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Credit card number
                        </label>
                        <input
                            type="text"
                            name="credit_card_number"
                            id="credit_card_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="***1234"
                            required=""
                            bind:this={credit_card_number_input}
                            bind:value={credit_card_number}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={credit_card_number_error_box}
                        >
                            Invalid credit card format
                        </p>
                    </div>
                    <div>
                        <label
                            for="credit_card_expiration"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Credit card expiration</label
                        >
                        <input
                            type="text"
                            name="credit_card_expiration"
                            id="credit_card_expiration"
                            placeholder="12/05"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            required=""
                            bind:this={credit_card_expiration_input}
                            bind:value={credit_card_expiration}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={credit_card_expiration_error_box}
                        >
                            Invalid credit card expiration
                        </p>
                    </div>
                    <div>
                        <label
                            for="credit_card_number"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Credit card CVV
                        </label>
                        <input
                            type="text"
                            name="credit_card_number"
                            id="credit_card_number"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="123"
                            required=""
                            bind:this={credit_card_cvv_input}
                            bind:value={credit_card_cvv}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={credit_card_cvv_error_box}
                        >
                            Invalid credit card format
                        </p>
                    </div>
                {:else if stage == 2}
                    <div>
                        <label
                            for="shipping_road"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Shipping address
                        </label>
                        <input
                            type="text"
                            name="shipping_road"
                            id="shipping_road"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Via le mani dal naso"
                            required=""
                            bind:this={shipping_address_input}
                            bind:value={shipping_address}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={shipping_address_error_box}
                        >
                            Invalid shipping address
                        </p>
                    </div>
                    <div>
                        <label
                            for="shipping_road"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Shipping City
                        </label>
                        <input
                            type="text"
                            name="shipping_road"
                            id="shipping_road"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Pisa"
                            required=""
                            bind:this={shipping_city_input}
                            bind:value={shipping_city}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={shipping_city_error_box}
                        >
                            Invalid shipping city
                        </p>
                    </div>
                    <div>
                        <label
                            for="shipping_road"
                            class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                            >Shipping State
                        </label>
                        <input
                            type="text"
                            name="shipping_road"
                            id="shipping_road"
                            class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                            placeholder="Italy"
                            required=""
                            bind:this={shipping_state_input}
                            bind:value={shipping_state}
                        />
                        <p
                            class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                            id="credit_card_number_error_box"
                            bind:this={shipping_state_error_box}
                        >
                            Invalid shipping state
                        </p>
                    </div>
                {:else if stage == 3}
                    <div class="bg-gray-200 rounded-lg m-1 p-4">
                        {#each $carrellazzo as book}
                            {book.name} | ${(book.price / 100).toFixed(2)} x {book.quantity}
                            <br />
                        {/each}
                        <br />
                        Total:
                        <span class="font-bold">${total.toFixed(2)}</span>
                    </div>
                    <button
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800 flex flex-col justify-center items-center button"
                        on:click|preventDefault={fire_form}
                    >
                        <img
                            src="data:image/png;base64,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"
                            alt=""
                        />
                    </button>
                {/if}
                {#if stage != 3}
                    <button
                        type="submit"
                        class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                        on:click|preventDefault={fire_form}>Next</button
                    >
                {/if}
            </form>
        </div>
    </div>
</div>

<Toast />

<style>
</style>
