<template>
	<form v-on:submit.prevent="handleSubmit"
		class="bg-zinc-100 shadow-sm rounded px-8 pt-6 pb-8 mb-4 sm:w-1/2 md:w-1/3">
		<div v-if="error" class="bg-red-500 text-white font-bold rounded-md py-2 px-4">
			{{ error }}
		</div>
		<div class="mb-4">
			<label class="block text-gray-700 text-sm font-bold mb-2" for="email">
				Email
			</label>
			<input
				class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
				:class="{ 'border-red-500': error }" id="email" v-model="email" type="email" placeholder="Email">
			<p class="mt-1 text-xs text-gray-500">Try: <a href="#" tabindex="-1"
					@click.prevent="loadEmailField">admin@pokemonmail.com</a></p>
		</div>
		<div class="mb-6">
			<label class="block text-gray-700 text-sm font-bold mb-2" for="password">
				Password
			</label>
			<input
				class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
				:class="{ 'border-red-500': error }" id="password" v-model="password" type="password"
				placeholder="Password">
			<p class="mt-1 text-xs text-gray-500">Try: <a href="#" tabindex="-1"
					@click.prevent="loadPasswordField">root</a></p>
		</div>
		<div class="flex items-center justify-between">
			<button
				class="bg-red-600 hover:bg-red-700 shadow-lg text-white font-semibold py-2 px-4 rounded-lg focus:outline-none focus:shadow-outline text-sm"
				type="submit" :disabled="isLoading" :class="{ 'bg-red-500': isLoading, 'hover:bg-red-500': isLoading }">
				Log In
			</button>
		</div>
	</form>
</template>

<script setup>

import { ref } from 'vue';

const email = ref('');
const password = ref('');
const error = ref('');
const isLoading = ref(false);
const emit = defineEmits(['user-authenticated']);

const loadEmailField = () => {
	email.value = 'admin@pokemonmail.com';
};
const loadPasswordField = () => {
	password.value = 'root';
};

const handleSubmit = async () => {
	isLoading.value = true;
	error.value = '';

	const response = await fetch('/login', {
		method: 'POST',
		headers: {
			'Content-Type': 'application/json'
		},
		body: JSON.stringify({
			email: email.value,
			password: password.value
		})
	});

	isLoading.value = false;

	if (!response.ok) {
		const data = await response.json();
		console.log(data);
		error.value = data.error;

		return;
	}

	email.value = '';
	password.value = '';
	const userIri = response.headers.get('Location');
	emit('user-authenticated', userIri);
}

</script>