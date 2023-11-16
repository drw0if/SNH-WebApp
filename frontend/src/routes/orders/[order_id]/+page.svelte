<script>
    import { page } from "$app/stores";
    import { onMount } from "svelte";
    import { add_cart } from "$lib/js/localstorage.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { Spinner } from "$lib/components/Spinner";
    import { get } from "$lib/js/api.js";

    $: order = null;

    onMount(async () => {
        let order_id = $page.params.order_id;

        // avoid CSPT and injections
        order_id = parseInt(order_id);
        if (isNaN(order_id)) {
            toast.set({ message: "Invalid order id" });
            return;
        }

        const res = await get(`/order.php?order_id=${order_id}`, true);
        if (res.status === 200) {
            order = await res.json();
        } else if (res.status === 404) {
            toast.set({ message: "Invalid order id" });
        } else {
            toast.set({ message: "Internal server error" });
        }

        console.log(order);
    });
</script>

<svelte:head>
    <title>just b00k | Order</title>
    <meta name="description" content="just b00k order description page" />
</svelte:head>

<div class="lg:mx-20 md:mx-10 my-10 px-3">
    {#if order}
        <div class="grid grid-cols-1 gap-4">
            <div>
                <h1
                    class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-5xl dark:text-white"
                >
                    Order #{order.order_id}
                </h1>
            </div>
            {#each order.items as book}
                <div class="flex flex-row m-10">
                    <div class="mr-10">
                        <a href="/book/{book.book_id}">
                            <img
                                class="mx-auto my-auto h-[20rem] border-2 border-black"
                                src="/books/{book.picture}"
                                alt="cover of book"
                                aria-label="Book cover for {book.name}"
                            />
                        </a>
                    </div>
                    <div class="lg:col-span-2">
                        <a
                            href="/book/{book.book_id}"
                            class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"
                        >
                            {book.name}
                        </a>

                        <div class="my-3">
                            <span
                                class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-1.5 rounded dark:bg-blue-200 dark:text-blue-800"
                                >From: {book.author}</span
                            >
                        </div>

                        <div class="my-1">
                            {#each book.genre.split(", ") as genre}
                                <span
                                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 my-10 mr-1"
                                    >{genre}</span
                                >
                            {/each}
                        </div>
                    </div>
                </div>
            {/each}
            <div class="flex flex-row justify-end">
                <h1
                    class="mb-4 text-3xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-5xl dark:text-white"
                >
                    ${order.total / 100}
                </h1>
            </div>
        </div>
    {:else}
        <Spinner />
    {/if}
</div>
<Toast />

<style>
</style>
