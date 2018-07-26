<template>
	<v-card>
		<v-card-title>
			Desarrollado por MUSERPOL {{ abc() }}
		</v-card-title>
	</v-card>
</template>
<script type="text/javascript">
	export default {
		
		methods: {
			abc () {
		      axios.get('company')
                .then(function (resp) {
                	console.log(resp.data);
                })
                .catch(function (resp) {
                    console.log(resp);
                    alert("Could not load employees");
                });
		    }
		}
	}
</script>