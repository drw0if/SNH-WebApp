<script>
    export let id;
    export let name;
    export let author;
    export let genre;
    export let picture;
    export let price;
    export let description;
</script>

<div
    class="flex flex-col justify-between w-full max-w-sm bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700"
>
    <div>
        <a href="/book/{id}" class="flex">
            <img
                class="p-8 rounded-t-lg mx-auto my-auto h-[30rem]"
                src="/books/{picture}"
                alt="cover of book"
                aria-label="Book cover for {name}"
            />
        </a>
        <div class="px-5 pb-5 block">
            <h5
                class="text-xl font-semibold tracking-tight text-gray-900 dark:text-white"
            >
                {name}
            </h5>
            <div class="flex items-center mt-2.5 mb-5">
                {#each genre.split(", ") as x}
                    <span
                        class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded dark:bg-blue-200 dark:text-blue-800 mr-1"
                        >{x}</span
                    >
                {/each}
            </div>
            <p class="mb-3 text-gray-500 dark:text-gray-400">
                {description.substring(0, 100).concat("...")}
            </p>
        </div>
    </div>
    <div class="px-5 pb-5">
        <span class="text-3xl font-bold text-gray-900 dark:text-white"
            >${(price/100).toFixed(2)}</span
        >
    </div>
</div>
