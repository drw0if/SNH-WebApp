<script>
	import Icon from "$lib/images/icon.png";
	import { validate_field } from "$lib/js/validation.js";
	import {Toast,toast} from "$lib/components/Toast";
	import { post } from "$lib/js/api.js";

	const fire_form = async () => {
		// Validate email
		const email_input = document.getElementById("email");
		const email_error_box = document.getElementById("email_error_box");
		const email = validate_field("email", email_input, email_error_box);

		// Validate username
		const username_input = document.getElementById("username");
		const username_error_box =
			document.getElementById("username_error_box");
		const username = validate_field(
			"username",
			username_input,
			username_error_box
		);

		// Validate password
		const password_input = document.getElementById("password");
		const password_error_box =
			document.getElementById("password_error_box");
		const password = validate_field(
			"password",
			password_input,
			password_error_box
		);

		// Validate confirm_password
		const confirm_password_input =
			document.getElementById("confirm_password");
		const confirm_password_error_box = document.getElementById(
			"password_confirm_error_box"
		);
		const confirm_password = validate_field(
			(confirm_password) => {
				return confirm_password === password;
			},
			confirm_password_input,
			confirm_password_error_box
		);

		const req = await post("/signup.php", {
			username,
			email,
			password,
		});

		if (req.status === 200) {
			document.location = "/login";
		} else {
			const res = await req.json();
			toast.set({ message: res.error})
		}
	};
</script>

<svelte:head>
	<title>just b00k | Register</title>
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
				Sign up to create an account
			</h1>
			<form class="space-y-4 md:space-y-6" action="#">
				<div>
					<label
						for="email"
						class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
						>Your email</label
					>
					<input
						type="email"
						name="email"
						id="email"
						class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
						placeholder="name@company.com"
						required=""
					/>
					<p
						class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
						id="email_error_box"
					>
						Invalid email format
					</p>
				</div>
				<div>
					<label
						for="username"
						class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
						>Your username</label
					>
					<input
						type="text"
						name="username"
						id="username"
						class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
						placeholder="username"
						required=""
					/>
					<p
						class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
						id="username_error_box"
					>
						Invalid username format
					</p>
				</div>
				<div>
					<label
						for="password"
						class="block mb-2 text-sm font-medium text-gray-900 dark:text-white"
						>Password</label
					>
					<input
						type="password"
						name="password"
						id="password"
						placeholder="••••••••"
						class="bg-gray-50 border border-gray-300 text-gray-900 sm:text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-blue-500 dark:focus:border-blue-500"
						required=""
					/>
					<p
						class="mt-2 text-sm text-red-600 dark:text-red-500 hidden"
						id="password_error_box"
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
						id="password_confirm_error_box"
					>
						Password mismatch
					</p>
				</div>
				<button
					type="submit"
					class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
					on:click|preventDefault={fire_form}>Sign up</button
				>
				<p class="text-sm font-light text-gray-500 dark:text-gray-400">
					Already have an account? <a
						href="/login"
						class="font-medium text-primary-600 hover:underline dark:text-primary-500"
						>Sign in</a
					>
				</p>
			</form>
		</div>
	</div>
</div>

<Toast />

<style>
</style>
