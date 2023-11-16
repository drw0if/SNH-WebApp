<script>
    import { onMount } from "svelte";
    import { get_token } from "$lib/js/localstorage.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { get } from "$lib/js/api.js";

    $: order = [];

    onMount(async () => {
        const token = get_token();
        if (token === null) {
            document.location = "/login";
            return;
        }

        const res = await get(`/order.php`, true);
        if (res.status !== 200) {
            toast.set({ message: "Internal server error" });
            return;
        }

        order = await res.json();
    });
</script>

<svelte:head>
    <title>just b00k | Books</title>
    <meta name="description" content="just b00k login page" />
</svelte:head>

<div class="mx-auto my-10 px-3">
    {#if order.length === 0}
        <p class="text-2xl font-bold text-center">No orders</p>
    {:else}
        <div
            class="grid grid-cols-1 md:grid-cols-1 lg:grid-cols-1 gap-x-[10rem] gap-y-10"
        >
            {#each order as order}
                <div
                    class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-3 gap-x-[10rem] gap-y-10 bg-white border border-gray-200 rounded-lg p-5"
                >
                    <div class="flex flex-row place-content-between">
                        <p class="mr-10">Shipping to:</p>
                        <div>
                            <p class="w-full text-right">{order.shipping_address}</p>
                            <p class="w-full text-right">{order.shipping_city}</p>
                            <p class="w-full text-right">{order.shipping_state}</p>
                        </div>
                    </div>
                    <div class="flex flex-row">
                        <h4 class="text-3xl font-extrabold dark:text-white">
                            total: ${order.total / 100}
                        </h4>
                    </div>
                    <div>
                        <a
                            href="/orders/{order.id}"
                            class="inline-flex items-center text-lg text-blue-600 dark:text-blue-500 hover:underline"
                        >
                            Go to order
                            <svg
                                class="w-3.5 h-3.5 ms-2 rtl:rotate-180"
                                aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg"
                                fill="none"
                                viewBox="0 0 14 10"
                            >
                                <path
                                    stroke="currentColor"
                                    stroke-linecap="round"
                                    stroke-linejoin="round"
                                    stroke-width="2"
                                    d="M1 5h12m0 0L9 1m4 4L9 9"
                                />
                            </svg>
                        </a>
                    </div>
                </div>
            {/each}
        </div>
    {/if}
</div>

<Toast />

<style>
</style>
