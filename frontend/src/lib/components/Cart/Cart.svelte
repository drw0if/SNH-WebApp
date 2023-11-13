<script>
    import { onDestroy } from "svelte";
    import { get_token } from "$lib/js/localstorage.js";
    import { cart } from "./store";
    import { get_cart, delete_cart } from "$lib/js/localstorage.js";

    $: show_cart = false;
    let carrellazzo = get_cart();

    $: total =
        $carrellazzo
            .map((x) => {
                return x.price * x.quantity;
            })
            .reduce((x, y) => {
                return x + y;
            }, 0) / 100;

    const unsub = cart.subscribe(() => {
        show_cart = !show_cart;
    });

    const token = get_token();

    onDestroy(unsub);
</script>

{#if show_cart}
    <div
        class="relative z-10"
        aria-labelledby="slide-over-title"
        role="dialog"
        aria-modal="true"
    >
        <div
            class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"
        />

        <div class="fixed inset-0 overflow-hidden">
            <div class="absolute inset-0 overflow-hidden">
                <div
                    class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10"
                    on:blur={() => {
                        cart.set({});
                    }}
                >
                    <div class="pointer-events-auto w-screen max-w-md">
                        <div
                            class="flex h-full flex-col overflow-y-scroll bg-white shadow-xl"
                        >
                            <div
                                class="flex-1 overflow-y-auto px-4 py-6 sm:px-6"
                            >
                                <div class="flex items-start justify-between">
                                    <h2
                                        class="text-lg font-medium text-gray-900"
                                        id="slide-over-title"
                                    >
                                        Shopping cart
                                    </h2>
                                    <div class="ml-3 flex h-7 items-center">
                                        <button
                                            type="button"
                                            class="relative -m-2 p-2 text-gray-400 hover:text-gray-500"
                                            on:click={() => {
                                                cart.set({});
                                            }}
                                        >
                                            <span class="absolute -inset-0.5" />
                                            <span class="sr-only"
                                                >Close panel</span
                                            >
                                            <svg
                                                class="h-6 w-6"
                                                fill="none"
                                                viewBox="0 0 24 24"
                                                stroke-width="1.5"
                                                stroke="currentColor"
                                                aria-hidden="true"
                                            >
                                                <path
                                                    stroke-linecap="round"
                                                    stroke-linejoin="round"
                                                    d="M6 18L18 6M6 6l12 12"
                                                />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div class="mt-8">
                                    <div class="flow-root">
                                        <ul
                                            role="list"
                                            class="-my-6 divide-y divide-gray-200"
                                        >
                                            {#each $carrellazzo as book}
                                                <li class="flex py-6">
                                                    <div
                                                        class="h-24 w-24 flex-shrink-0 overflow-hidden rounded-md border border-gray-200"
                                                    >
                                                        <img
                                                            src="/books/{book.picture}"
                                                            alt="Book cover for {book.name}"
                                                            class="h-full w-full object-cover object-center"
                                                        />
                                                    </div>

                                                    <div
                                                        class="ml-4 flex flex-1 flex-col"
                                                    >
                                                        <div>
                                                            <div
                                                                class="flex justify-between text-base font-medium text-gray-900"
                                                            >
                                                                <h3>
                                                                    <a href="#"
                                                                        >{book.name}</a
                                                                    >
                                                                </h3>
                                                                <p class="ml-4">
                                                                    ${(
                                                                        book.price /
                                                                        100
                                                                    ).toFixed(
                                                                        2
                                                                    )}
                                                                </p>
                                                            </div>
                                                            <p
                                                                class="mt-1 text-sm text-gray-500"
                                                            >
                                                                {book.author}
                                                            </p>
                                                        </div>

                                                        <div
                                                            class="flex flex-1 items-end justify-between text-sm"
                                                        >
                                                            <p
                                                                class="text-gray-500"
                                                            >
                                                                Qty {book.quantity}
                                                            </p>

                                                            <div class="flex">
                                                                <button
                                                                    type="button"
                                                                    class="font-medium text-blue-600 hover:text-blue-500"
                                                                    on:click={() =>
                                                                        delete_cart(
                                                                            book.id
                                                                        )}
                                                                    >Remove</button
                                                                >
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            {/each}
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <div
                                class="border-t border-gray-200 px-4 py-6 sm:px-6"
                            >
                                <div
                                    class="flex justify-between text-base font-medium text-gray-900"
                                >
                                    <p>Subtotal</p>
                                    <p>
                                        ${total.toFixed(2)}
                                    </p>
                                </div>
                                <p class="mt-0.5 text-sm text-gray-500">
                                    Shipping and taxes calculated at checkout.
                                </p>
                                <div class="mt-6">
                                    <button
                                        href="/asd"
                                        class="rounded-md border border-transparent bg-blue-600 px-6 py-3 text-base font-medium text-white shadow-sm w-full hover:bg-blue-700 disabled:cursor-not-allowed disabled:hover:none
                                        disabled:opacity-10"
                                        disabled={total == 0}
                                        on:click={() => {
                                            if (token === null) {
                                                document.location = "/login";
                                            } else {
                                                document.location = "/checkout";
                                            }
                                        }}
                                        >{"Checkout"}</button
                                    >
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
{/if}
