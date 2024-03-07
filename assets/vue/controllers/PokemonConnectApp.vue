<template>
	<div class="flex flex-col min-h-screen">
		<div class="px-8 py-8">
			<img class="h-16 w-auto" :src="pokeballLogoPath" alt="Symfony Pokemon API Logo">
		</div>
		<div class="flex-auto flex flex-col sm:flex-row justify-center px-8">
			<LoginForm v-on:user-authenticated="onUserAuthenticated"></LoginForm>
			<div class="bg-zinc-200 shadow-md rounded sm:ml-3 px-8 pt-8 pb-8 mb-4 sm:w-1/2 md:w-1/3 text-center">
				<div v-if="user">
					Authenticated as: <strong>{{ user.username }}</strong>

					| <a href="/logout" class="underline">Log out</a>
				</div>
				<div v-else>Not authenticated</div>

				<hr class="my-10 mx-auto" style="border-top: 1px solid #ccc; width: 70%;" />

				<p>Check out the <a :href="entrypoint" class="underline">API Docs</a></p>
			</div>
		</div>
		<img :src="pokemonBackgroundPath" alt="A Pokemon Collection !">
	</div>
</template>

<script setup>
import { ref } from 'vue';
import LoginForm from '../LoginForm';
import pokeballLogoPath from '../../images/pokeball-logo.svg';
import pokemonBackgroundPath from '../../images/pokemon-background2.png';

defineProps(['entrypoint']);
const user = ref(window.user);

const onUserAuthenticated = async (userUri) => {
	const response = await fetch(userUri);
	user.value = await response.json();
}
</script>