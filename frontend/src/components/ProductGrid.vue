<template>
    <div class="product-grid">
        <div class="header">
            <h1 class="product-list-title">Product List</h1>
            <div class="buttons">
                <button id="add-product-btn" @click="addProductListener">ADD</button>
                <button id="delete-product-btn" @click="massDelete">MASS DELETE</button>
            </div>
        </div>
        <div class="grid">
            <div
                    v-for="(product, index) in products"
                    :key="index"
                    class="product-card"
            >
                <input type="checkbox" class="delete-checkbox"/>
                <div class="product-info">
                    <p>{{ product.sku }}</p>
                    <p>{{ product.name }}</p>
                    <p>{{ product.price }}</p>
                    <p>{{ product.attribute }}</p>
                </div>
            </div>
        </div>
        <footer class="footer">Simple Footer</footer>
    </div>
</template>

<script setup>
import {ref} from 'vue';
import {useRouter} from 'vue-router';
import {API_BASE_URL} from "@/config";

const BASE_URL = API_BASE_URL || "http://localhost:8000"
const url = `${BASE_URL}/products/getAll`;
// let url = `${API_BASE_URL}/products/getAll`;

const products = ref([]);

getProducts();

function getProducts() {
    fetch(url, {
        method: 'GET',
    })
        .then(response => {
            if (response.ok) {
                return response.json();
            } else {
                console.error('Failed to get products; ', response.status);
                throw new Error('Failed to get products');
            }
        })
        .then(data => {
            products.value = data;
        })
        .catch(error => console.error('Error:', error));
}

const router = useRouter();

function addProductListener() {
    router.push('/addProduct');
}

function massDelete() {
    let skus = [];
    let checkboxes = document.getElementsByClassName('delete-checkbox');

    for (let i = 0; i < checkboxes.length; i++) {
        if (checkboxes[i].checked) {
            let sku = checkboxes[i].parentElement.querySelector('.product-info p').textContent;
            skus.push(sku);
        }
    }
    // manual serialization (probably temporary)
    // const encodedSkus = encodeURIComponent(JSON.stringify(skus));
    // "%5B%22" + "%22,%22".join(skus) + "%22%5D"
    const url = `${BASE_URL}/products/delete?skus=${"%5B%22" + skus.join("%22,%22") + "%22%5D"}`
    // const url = `${BASE_URL}/products/delete?skus=${encodedSkus}` // again, using manual version for now
    fetch(url, {
        method: 'DELETE',
    }).then(response => {
        if (response.ok) {
            let boxes = document.getElementsByClassName("delete-checkbox")
            for (let i = 0; i < boxes.length; i++) {
                boxes[i].checked = false;
            }
            getProducts();
        } else {
            console.log("error", response.text());
        }
    })
}
</script>

<style scoped>

.product-grid {
    display: flex;
    flex-direction: column;
    align-items: center;
    height: 100%;
    justify-content: space-between;
}

.grid {
    height: 100%;
    overflow: auto;
    scroll-behavior: smooth;
    width: 75vw;
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(13vw, 13vw));
    min-height: 25vh;
    grid-gap: 20px;
}

.product-card {
    display: grid;
    grid-template-rows: auto .8fr;
    border: 4px solid #282828;
    padding: 20px;
    height: 12vw;
    background: #2f2e2e;
    border-radius: 10px;
}

.product-info {
    height: 100%;
    display: grid;
    padding: 10% 0;
    font-size: 20px;
    justify-content: center;
    align-content: space-evenly;
    justify-items: center;
}

/*
.product-info p {
    overflow: hidden;
    display: flex;
    align-items: center;
    flex-direction: column;
}
*/
.delete-checkbox {
    display: flex;
    align-items: center;
    margin-right: 10px;
    width: 18px;
    height: 18px;
    border-radius: 50%;
    overflow: hidden;
}

.delete-checkbox input[type="checkbox"] {
    display: none;
}

.delete-checkbox label {
    display: block;
    width: 100%;
    height: 100%;
    border: 2px solid #0e3128;
    border-radius: 4px;
    transition: all 0.2s ease-in-out;
}

.delete-checkbox input[type="checkbox"]:checked + label {
    background-color: #333;
    border-color: #333;
}

.delete-checkbox label:before {
    content: "";
    display: block;
    width: 10px;
    height: 10px;
    margin: 3px;
    border-radius: 2px;
    background-color: white;
    transition: all 0.2s ease-in-out;
}

.delete-checkbox input[type="checkbox"]:checked + label:before {
    transform: translateX(6px);
}

.product-info {
    margin-top: auto;
}

.product-list-title {
    margin: 0;
}

.buttons {
    display: flex;
}


</style>
