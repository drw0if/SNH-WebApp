<script>
    import Icon from "$lib/images/icon.png";
    import { validate_field } from "$lib/js/validation.js";
    import { Toast, toast } from "$lib/components/Toast";
    import { post } from "$lib/js/api.js";

    const fire_form = async () => {
        // validate old password
        const current_password_input = document.getElementById("current_password");
        const current_password_error_box = document.getElementById(
            "current_password_error_box"
        );
        const current_password = validate_field(
            "password",
            current_password_input,
            current_password_error_box
        );

        // Validate new_password
        const new_password_input = document.getElementById("new_password");
        const new_password_error_box =
            document.getElementById("new_password_error_box");
        const new_password = validate_field(
            "password",
            new_password_input,
            new_password_error_box
        );

        // Validate confirm_password
        const confirm_password_input =
            document.getElementById("confirm_password");
        const confirm_password_error_box = document.getElementById(
            "confirm_password_error_box"
        );
        const confirm_password = validate_field(
            (confirm_password) => {
                return confirm_password === new_password;
            },
            confirm_password_input,
            confirm_password_error_box
        );

        
        const req = await post("/change_password.php", {
            current_password,
            new_password,
        },true);

        if (req.status === 200) {
            document.location = "/book";
        } else {
            const res = await req.json();
            toast.set({ message: res.error });
        }
    };
</script>

<svelte:head>
    <title>just b00k | Change password</title>
    <meta name="description" content="just b00k registration page" />
</svelte:head>

<div
    class="flex flex-col items-center justify-center px-6 py-8 mx-auto my-auto lg:py-0"
>
    <a
        href="#"
        class="flex items-center my-6 text-2xl font-semibold text-gray-900 dark:text-white"
    >
        <img class="w-8 h-8 mr-2" src={Icon} alt="logo" />
        Just b00k
    </a>
    <div
        class="w-full bg-white rounded-lg shadow dark:border md:mt-0 sm:max-w-md xl:p-0 dark:bg-gray-800 dark:border-gray-700"
    >
        <div class="p-6 space-y-4 md:space-y-6 sm:p-8">
            <h1
                class="text-xl font-bold leading-tight tracking-tight text-gray-900 md:text-2xl dark:text-white"
            >
                Change password
            </h1>
            <form class="space-y-4 md:space-y-6" action="#">
                <div>
                    <label
                        for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Old password</label
                    >
                    <input
                        type="password"
                        name="current_password"
                        id="current_password"
                        placeholder="••••••••"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required=""
                    />
                    <p
                        class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                        id="current_password_error_box"
                    >
                        Invalid password format: at least an uppercase, a
                        lowercase, a number and a symbol
                    </p>
                </div>
                <div>
                    <label
                        for="password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >New password</label
                    >
                    <input
                        type="password"
                        name="new_password"
                        id="new_password"
                        placeholder="••••••••"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required=""
                    />
                    <p
                        class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                        id="new_password_error_box"
                    >
                        Invalid password format: at least an uppercase, a
                        lowercase, a number and a symbol
                    </p>
                </div>
                <div>
                    <label
                        for="confirm_password"
                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
                        >Confirm password</label
                    >
                    <input
                        type="password"
                        name="confirm_password"
                        id="confirm_password"
                        placeholder="••••••••"
                        class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
                        required=""
                    />
                    <p
                        class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
                        id="confirm_password_error_box"
                    >
                        Password mismatch
                    </p>
                </div>
                <button
                    type="submit"
                    class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
                    on:click|preventDefault={fire_form}
                    >Change</button
                >
            </form>
        </div>
    </div>
</div>

<Toast />

<style>
</style>
