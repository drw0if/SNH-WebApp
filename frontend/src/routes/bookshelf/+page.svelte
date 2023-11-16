<script>
    import { onMount } from "svelte";
    import { Toast, toast } from "$lib/components/Toast";
    import { get } from "$lib/js/api.js";

    $: books = [];

    onMount(async () => {
        const res = await get(`/bookshelf.php`, true);
        if (res.status !== 200) {
            toast.set({ message: "Internal server error" });
        }

        books = await res.json();
    });

    const download_file = async (book_id) => {
        const res = await get(`/download.php?book_id=${book_id}`, true);
        if (res.status === 401) {
            toast.set({ message: "You don't own this book" });
            return;
        }

        if (res.status !== 200) {
            toast.set({ message: "Internal server error" });
            return;
        }

        // Get the filename from the Content-Disposition header
        const content_disposition = res.headers.get("Content-Disposition");
        const filename = content_disposition
            .split("filename=")[1]
            .replaceAll('"', "");

        // Convert the response to a Blob
        const blob = await res.blob();

        // Create a link element to trigger the download
        const link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = filename || "ebook.pdf";
        document.body.appendChild(link);
        link.click();
        document.body.removeChild(link);
    };
</script>

<svelte:head>
    <title>just b00k | Books</title>
    <meta name="description" content="just b00k login page" />
</svelte:head>

<div class="mx-auto my-10 px-3">
    <div
        class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-x-[10rem] gap-y-10"
    >
        {#each books as book}
            <div
                class="flex flex-col justify-between w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
            >
                <div>
                    <a href="/book/{book.id}" class="flex">
                        <img
                            class="p-3 rounded-t-lg mx-auto my-auto h-[25rem]"
                            src="/books/{book.picture}"
                            alt="cover of book"
                            aria-label="Book cover for {book.name}"
                        />
                    </a>
                    <div class="px-5 pb-5 block">
                        <h5
                            class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white"
                        >
                            {book.name}
                        </h5>
                    </div>
                </div>
                <div>
                    <div class="px-5 pb-5 flex justify-center">
                        <button
                            on:click={() => {
                                download_file(book.id);
                            }}
                        >
                            <svg
                                xmlns="http://www.w3.org/2000/svg"
                                width="16"
                                height="16"
                                fill="currentColor"
                                class="w-8 h-8"
                                viewBox="0 0 16 16"
                            >
                                <path
                                    d="M.5 9.9a.5.5 0 0 1 .5.5v2.5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1v-2.5a.5.5 0 0 1 1 0v2.5a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2v-2.5a.5.5 0 0 1 .5-.5z"
                                />
                                <path
                                    d="M7.646 11.854a.5.5 0 0 0 .708 0l3-3a.5.5 0 0 0-.708-.708L8.5 10.293V1.5a.5.5 0 0 0-1 0v8.793L5.354 8.146a.5.5 0 1 0-.708.708l3 3z"
                                />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        {/each}
    </div>
</div>

<Toast />

<style>
</style>
