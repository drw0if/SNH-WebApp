<script>
    import { page } from "$app/stores";
    import { onMount } from "svelte";
    import { add_cart } from "$lib/js/localstorage.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { Spinner } from "$lib/components/Spinner";
    import { get } from "$lib/js/api.js";

    $: book = null;

    onMount(async () => {
        let book_id = $page.params.book_id;

        // avoid CSPT and injections
        book_id = parseInt(book_id);
        if (isNaN(book_id)) {
            toast.set({ message: "Invalid book id" });
            return;
        }

        const res = await get(`/book.php?book_id=${book_id}`);
        if (res.status === 200) {
            book = await res.json();
        } else if (res.status === 404) {
            toast.set({ message: "Invalid book id" });
        } else {
            toast.set({ message: "Internal server error" });
        }
    });

    const add_to_cart = () => {
        add_cart(book);
        //document.location = "/book";
    };
</script>

<svelte:head>
    <title>just b00k | Book</title>
    <meta name="description" content="just b00k book description page" />
</svelte:head>

<div class="lg:mx-20 md:mx-10 my-10 px-3">
    {#if book}
        <div class="grid lg:grid-cols-3 md:grid-cols-1 gap-4">
            <div>
                <img
                    class="mx-auto my-auto h-[30rem] border-2 border-black"
                    src="/books/{book.picture}"
                    alt="cover of book"
                    aria-label="Book cover for {book.name}"
                />
            </div>
            <div class="lg:col-span-2">
                <h1
                    class="mb-4 text-4xl font-extrabold leading-none tracking-tight text-gray-900 md:text-5xl lg:text-6xl dark:text-white"
                >
                    {book.name}
                </h1>

                <div class="my-1">
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

                {#each book.description.split("\n") as line}
                    <p class="mb-3 text-gray-500 dark:text-gray-400">{line}</p>
                {/each}

                <div class="flex flex-row justify-end my-3 gap-3">
                    <span
                        class="text-3xl font-bold text-gray-900 dark:text-white"
                        >${(book.price / 100).toFixed(2)}</span
                    >
                    <a
                        href="#"
                        class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800"
                        on:click={add_to_cart}>Add to cart</a
                    >
                </div>
            </div>
        </div>
    {:else}
        <Spinner />
    {/if}
</div>
<Toast />

<style>
</style>
