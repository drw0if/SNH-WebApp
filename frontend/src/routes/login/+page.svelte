<script>
	import Icon from "$lib/images/icon.png";
	import { set_token } from "$lib/js/localstorage.js";
	import { Toast, toast } from "$lib/components/Toast";
	import { post } from "$lib/js/api.js";

	let username = "";
	let password = "";

	const fire_form = async () => {
		const req = await post("/signin.php", {
			username,
			password,
		});

		const res = await req.json();

		if (req.status === 200) {
			const token = res.token;
			set_token(token);
			document.location = "/book";
		} else {
			toast.set({ message: res.error });
		}
	};
</script>

<svelte:head>
	<title>just b00k | Login</title>
	<meta name="description" content="just b00k login page" />
</svelte:head>

<div
	class="flex flex-col items-center justify-center px-6 py-8 mx-auto my-auto lg:py-0"
>
	<a
		href="#"
		class="flex items-center mb-6 text-2xl font-semibold text-gray-900 dark:text-white"
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
				Sign in to read your books
			</h1>
			<form class="space-y-4 md:space-y-6" action="#">
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
						bind:value={username}
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
						bind:value={password}
					/>
				</div>
				<button
					type="submit"
					class="w-full text-white bg-primary-600 hover:bg-primary-700 focus:ring-4 focus:outline-none focus:ring-primary-300 font-medium rounded-lg text-sm px-5 py-2.5 text-center dark:bg-primary-600 dark:hover:bg-primary-700 dark:focus:ring-primary-800"
					on:click|preventDefault={fire_form}>Sign in</button
				>
				<p class="text-sm font-light text-gray-500 dark:text-gray-400">
					Don't have an account yet? <a
						href="/register"
						class="font-medium text-primary-600 hover:underline dark:text-primary-500"
						>Sign up</a
					>
				</p>
				<p class="text-sm font-light text-gray-500 dark:text-gray-400">
					Forgot your password? <a
						href="/recover_password"
						class="font-medium text-primary-600 hover:underline dark:text-primary-500"
						>Recover it</a
					>
				</p>
			</form>
		</div>
	</div>
</div>

<Toast />

<style>
</style>
