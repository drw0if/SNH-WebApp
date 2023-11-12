<script>
    import { onMount } from "svelte";
    import { get_token, set_token } from "$lib/js/localstorage.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { Card } from "$lib/components/Card";
    import { get } from "$lib/js/api.js";

    $: books = [];

    onMount(async () => {
        const res = await get(`/book.php`);
        if (res.status !== 200) {
            toast.set({ message: "Internal server error" });
        }

        books = await res.json();
    });
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
            <Card {...book} />
        {/each}
    </div>
</div>

<Toast />

<style>
</style>
