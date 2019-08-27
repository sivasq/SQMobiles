<template>
    <div class="container" id="product_form">
        <div class="row justify-content-md-center">
            <div class="col-12 mt-5">
                <input class="form-control mb-1" placeholder="Filter By Brand" type="search" v-model="searchBrand">
                <ul class="list-group">
                    <li class="list-group-item text-center text-primary"> Products List</li>
                    <li class="list-group-item text-center text-danger" v-if='products.length === 0'>There are no
                        Product yet!
                    </li>
                    <li :id="product.id" :key="product.id" class="list-group-item" v-for="(product, index) in
                    filterProducts">
                        <div class="row">
                            {{index + 1}}) {{product.brand_details.brand_name}} {{product.product_name}}
                            &nbsp;&nbsp;
                            <input class="form-control" id="id" placeholder="Product id" readonly style="width: 70px;"
                                   type="text"
                                   v-model="product.id">
                            &nbsp;
                            <input class="form-control" id="price" placeholder="price" style="width: 200px;"
                                   type="text"
                                   v-model="product.price">
                            &nbsp;
                            <input class="form-control" id="gst" placeholder="Gst" style="width: 100px;"
                                   type="text"
                                   v-model="product.gst">
                            &nbsp;
                            <button @click="setPrice(product.id)" class="btn btn-sm btn-outline-primary">
                                set Price
                            </button>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
<script>
	export default {
		data() {
			return {
				product: {
					brand_id: '',
					product_name: ''
				},
				has_error: false,
				error: '',
				errors: {},
				success: false,
				brands: [],
				products: [],
				action: 'addProduct',
				product_id: '',
				isUpdate: false,
				searchBrand: '',
			}
		},
		created() {
			this.fetchProducts();
		},
		computed: {
			filterProducts() {
				return this.products.filter(stock => {
					return !this.searchBrand.trim() ||
						stock.brand_details.brand_name.toLowerCase().indexOf(this.searchBrand.toLowerCase().trim()) > -1
					// return client.imei_number.indexOf(this.searchClient) > -1;
				});
			}
		},
		methods: {
			setPrice(p_id) {
				let id = p_id;
				let price = $("#" + p_id + " #price").val();
				let gst = $("#" + p_id + " #gst").val();

				axios.post(window.base_url + '/api/v1/auth/setPrice', {id: id, price: price, gst: gst})
					.then(response => {
						if (response.data.status === 'success') {
							Vue.$toast.success("price Added");
						}
					})
					.catch((res) => {
					});
			},
			fetchProducts() {
				axios.get(window.base_url + '/api/v1/auth/fetchProducts')
					.then(response => {
						this.products = response.data.data;
						console.log(this.products);
					})
					.catch((err) => console.error(err));
			},
		},
		components: {
			//
		}
	}
</script>

