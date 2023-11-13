<script>
    import "../app.css";
    import Icon from "$lib/images/icon.png";
    import { get_cart } from "$lib/js/localstorage.js";
    import { logout, get_token } from "$lib/js/localstorage.js";
    import { Cart, cart } from "$lib/components/Cart";

    let carrellazzo = get_cart();

    let show_menu = false;
    const toggle_menu = () => {
        show_menu = !show_menu;
    };

    const fire_logout = () => {
        logout();
        document.location = "/";
    };

    const token = get_token();
</script>

<svelte:head>
    <title>Home</title>
    <meta name="description" content="just b00k" />
</svelte:head>

<section
    class="bg-gray-50 dark:bg-gray-900 min-h-screen flex flex-col justify-between"
>
    <nav
        class="bg-white border-gray-200 dark:border-gray-600 dark:bg-gray-900 border-y"
    >
        <div
            class="flex flex-wrap justify-between items-center mx-auto max-w-screen-xl p-4"
        >
            <a href="/" class="flex items-center space-x-3 rtl:space-x-reverse">
                <img src={Icon} class="h-8" alt="just b00k logo" />
                <span
                    class="self-center text-2xl font-semibold whitespace-nowrap dark:text-white"
                    >just b00k</span
                >
            </a>
            <button
                data-collapse-toggle="mega-menu-full"
                type="button"
                class="inline-flex items-center p-2 w-10 h-10 justify-center text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600"
                aria-controls="mega-menu-full"
                aria-expanded="false"
                on:click={toggle_menu}
            >
                <span class="sr-only">Open main menu</span>
                <svg
                    class="w-5 h-5"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="none"
                    viewBox="0 0 17 14"
                >
                    <path
                        stroke="currentColor"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M1 1h15M1 7h15M1 13h15"
                    />
                </svg>
            </button>
            <div
                id="mega-menu-full"
                class="items-center justify-between {show_menu
                    ? ''
                    : 'hidden'} font-medium w-full md:flex md:w-auto md:order-1"
            >
                <ul
                    class="flex flex-col p-4 md:p-0 mt-4 border border-gray-100 rounded-lg bg-gray-50 md:space-x-8 rtl:space-x-reverse md:flex-row md:mt-0 md:border-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900 dark:border-gray-700"
                >
                    <li>
                        <a
                            href="/"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            aria-current="page">Home</a
                        >
                    </li>
                    <li>
                        <a
                            href="/book"
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            >Books</a
                        >
                    </li>
                    {#if token != null}
                        <li>
                            <a
                                href="/bookshelf"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                >Bookshelf</a
                            >
                        </li>
                        <li>
                            <a
                                href="/profile"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                >Profile</a
                            >
                        </li>
                        <li>
                            <a
                                href="/orders"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                >Orders</a
                            >
                        </li>
                        <li>
                            <a
                                href="/logout"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                on:click|preventDefault={fire_logout}>Logout</a
                            >
                        </li>
                    {:else}
                        <li>
                            <a
                                href="/login"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                >Login</a
                            >
                        </li>

                        <li>
                            <a
                                href="/register"
                                class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                                >Register</a
                            >
                        </li>
                    {/if}
                    <li>
                        <button
                            class="block py-2 px-3 text-gray-900 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-blue-700 md:p-0 dark:text-white md:dark:hover:text-blue-500 dark:hover:bg-gray-700 dark:hover:text-blue-500 md:dark:hover:bg-transparent dark:border-gray-700"
                            on:click={() => {
                                cart.set({});
                            }}
                        >
                            <div class="flex justify-center items-center">
                                <div class="relative">
                                    {#if $carrellazzo.length > 0}
                                        <div class="t-0 absolute left-3">
                                            <p
                                                class="flex h-2 w-2 items-center justify-center rounded-full bg-red-500 p-3 text-xs text-white"
                                            >
                                                {$carrellazzo.length}
                                            </p>
                                        </div>
                                    {/if}
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke-width="1.5"
                                        stroke="currentColor"
                                        class="w-6"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 00-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 00-16.536-1.84M7.5 14.25L5.106 5.272M6 20.25a.75.75 0 11-1.5 0 .75.75 0 011.5 0zm12.75 0a.75.75 0 11-1.5 0 .75.75 0 011.5 0z"
                                        />
                                    </svg>
                                </div>
                            </div>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
        <Cart />
    </nav>

    <slot />

    <footer class="bg-white rounded-lg shadow m-4 dark:bg-gray-800">
        <div
            class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between"
        >
            <span
                class="text-sm text-gray-500 sm:text-center dark:text-gray-400"
                >© 2023 <a
                    href={document.location.origin}
                    class="hover:underline">Compagni di merende™</a
                >. All Rights Reserved.
            </span>
            <ul
                class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0"
            >
                <li>
                    <a href="/about" class="hover:underline me-4 md:me-6"
                        >About</a
                    >
                </li>
                <li>
                    <a
                        href="https://github.com/drw0if/SNH-WebApp"
                        class="hover:underline me-4 md:me-6">Source</a
                    >
                </li>
            </ul>
        </div>
    </footer>
</section>

<style>
</style>
