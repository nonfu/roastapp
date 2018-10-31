<style lang="scss">
    @import '~@/abstracts/_variables.scss';

    div#admin-brew-method {
        label {
            font-weight: bold;
            color: black;
            font-size: 16px;
            margin-top: 15px;
        }
        a.update-brew-method {
            display: block;
            width: 150px;
            color: white;
            background-color: #CCC;
            text-align: center;
            border-radius: 5px;
            margin-top: 20px;
            height: 45px;
            line-height: 45px;
            margin-bottom: 100px;
        }
        img.brew-method-icon {
            width: 50px;
        }
        a.change-icon {
            display: block;
            margin-top: 10px;
            color: $secondary-color;
        }
        div.change-icon-modal {
            position: fixed;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            background-color: rgba(0, 0, 0, .6);
            z-index: 99999;
            div.modal-box {
                width: 100%;
                max-width: 530px;
                min-width: 320px;
                padding: 20px;
                background-color: #fff;
                border: 1px solid #ddd;
                -webkit-box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
                box-shadow: 0 1px 3px rgba(50, 50, 50, 0.08);
                -webkit-border-radius: 4px;
                border-radius: 4px;
                font-size: 16px;
                position: absolute;
                top: 50%;
                left: 50%;
                transform: translate(-50%, -50%);
                max-height: 500px;
                overflow-y: auto;
                label {
                    font-weight: bold;
                }
                div.icon-selection-container {
                    margin-top: 10px;
                }
                div.new-icon-container {
                    text-align: center;
                    cursor: pointer;
                    margin-bottom: 20px;
                    border-radius: 5px;
                    padding: 5px;
                    &.active {
                        background-color: $secondary-color;
                        color: white;
                    }
                    img.new-icon {
                        display: block;
                        margin: auto;
                        margin-bottom: 10px;
                        height: 30px;
                    }
                }
            }
        }
    }
</style>

<template>
    <div id="admin-brew-method">
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-12 medium-12 cell">
                    <h3 class="page-header">
                        <router-link :to="{ name: 'admin-brew-methods'}">所有冲泡方法</router-link>
                        > {{ brewMethod.method }}
                    </h3>
                </div>
            </div>
        </div>
        <div class="grid-container">
            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>方法名</label>
                    <input type="text" v-model="method"/>
                    <span class="validation" v-show="!validations.method">请输入冲泡方法名称</span>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <label>图标</label>
                    <img class="brew-method-icon" v-bind:src="icon !== '' ? icon : ''"/>
                    <a v-on:click="promptChangeIcon()" class="change-icon">修改图标</a>
                </div>
            </div>

            <div class="grid-x">
                <div class="large-8 medium-12 cell">
                    <a class="update-brew-method" v-on:click="updateBrewMethod()">更新</a>
                </div>
            </div>
        </div>

        <div class="change-icon-modal" v-show="showChangeIcon" v-on:click="hideChangeIcon()">
            <div class="modal-box" v-on:click.stop="">
                <div class="grid-x icon-selection-container">
                    <div class="large-12 medium-12 cell">
                        <label>图标</label>
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/aeropress.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/aeropress.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/aeropress.svg"/>
                        Aeropress
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/biscuit.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/biscuit.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/biscuit.svg"/>
                        Biscuit
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/bottle-of-water.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/bottle-of-water.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/bottle-of-water.svg"/>
                        Bottle of Water
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/chemex.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/chemex.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/chemex.svg"/>
                        Chemex
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/chocolate.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/chocolate.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/chocolate.svg"/>
                        Chocolate
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/cinnamon.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/cinnamon.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/cinnamon.svg"/>
                        Cinnamon
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/clover.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/clover.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/clover.svg"/>
                        Clover
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-app.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-app')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-app.svg"/>
                        Coffee App
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-beans.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-beans')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-beans.svg"/>
                        Coffee Beans
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-capsules.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-capsules')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-capsules.svg"/>
                        Coffee Capsules
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-coctail.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-coctail.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-cocktail.svg"/>
                        Coffee Cocktail
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-cup.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-cup.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-cup.svg"/>
                        Coffee Cup
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-is-love.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-is-love.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-is-love.svg"/>
                        Coffee Is Love
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-mill.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-mill.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-mill.svg"/>
                        Coffee Mill
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-pack.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-pack.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-pack.svg"/>
                        Coffee Pack
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-pods.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-pods.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-pods.svg"/>
                        Coffee Pods
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-pot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-pot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-pot.svg"/>
                        Coffee Pot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-scoop.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-scoop.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-scoop.svg"/>
                        Coffee Scoop
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-shake.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-shake.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-shake.svg"/>
                        Coffee Shake
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-shop-sign.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-shop-sign.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-shop-sign.svg"/>
                        Coffee Shop Sign
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-shop.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-shop.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-shop.svg"/>
                        Coffee Shop
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-to-go.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-to-go.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-to-go.svg"/>
                        Coffee To Go
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-tree.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-tree.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-tree.svg"/>
                        Coffee Tree
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-with-cream.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-with-cream.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-with-cream.svg"/>
                        Coffee With Cream
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffee-with-ice-cream.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffee-with-ice-cream.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffee-with-ice-cream.svg"/>
                        Coffee With Ice Cream
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/coffeemaker.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/coffeemaker.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/coffeemaker.svg"/>
                        Coffee Maker
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/cold-brew.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/cold-brew.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/cold-brew.svg"/>
                        Cold Brew
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/croissant.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/croissant.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/croissant.svg"/>
                        Croissant
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/cupcake.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/cupcake.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/cupcake.svg"/>
                        Cupcake
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/donut.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/donut.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/donut.svg"/>
                        Donut
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/drip-brew.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/drip-brew.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/drip-brew.svg"/>
                        Drip Brew
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/drip-kettle.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/drip-kettle.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/drip-kettle.svg"/>
                        Drip Kettle
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/electric-kettle.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/electric-kettle.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/electric-kettle.svg"/>
                        Electric Kettle
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/espresso-cup.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/espresso-cup.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/espresso-cup.svg"/>
                        Espresso Cup
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/espresso-tamper.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/espresso-tamper.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/espresso-tamper.svg"/>
                        Espresso Tamper
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/espresso.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/espresso.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/espresso.svg"/>
                        Espresso
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/eva-solo.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/eva-solo.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/eva-solo.svg"/>
                        Eva Solo
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/fizzy-water.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/fizzy-water.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/fizzy-water.svg"/>
                        Fizzy Water
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/frappe.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/frappe.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/frappe.svg"/>
                        Frappe
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/french-press.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/french-press.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/french-press.svg"/>
                        French Press
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/frozen-frappe.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/frozen-frappe.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/frozen-frappe.svg"/>
                        Frozen Frappe
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/green-tea.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/green-tea.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/green-tea.svg"/>
                        Green Tea
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/hario.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/hario.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/hario.svg"/>
                        Hario
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/hot-chocolate.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/hot-chocolate.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/hot-chocolate.svg"/>
                        Hot Chocolate
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/i-love-coffee.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/i-love-coffee.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/i-love-coffee.svg"/>
                        I Love Coffee
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/iced-coffee_2.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/iced-coffee_2.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/iced-coffee_2.svg"/>
                        Iced Coffee
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/iced-tea.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/iced-tea.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/iced-tea.svg"/>
                        Iced Tea
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/instant-coffee.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/instant-coffee.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/instant-coffee.svg"/>
                        Instant Coffee
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/kalita-wave.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/kalita-wave.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/kalita-wave.svg"/>
                        Kalita Wave
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/kettle.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/kettle.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/kettle.svg"/>
                        Kettle
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/latte_2.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/latte_2.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/latte_2.svg"/>
                        Latte
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/latte.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/latte.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/latte.svg"/>
                        Latte
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/matcha-latte.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/matcha-latte.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/matcha-latte.svg"/>
                        Matcha Latte
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/milk-pitcher.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/milk-pitcher.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/milk-pitcher.svg"/>
                        Milk Pitcher
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/milk-product.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/milk-product.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/milk-product.svg"/>
                        Milk Product
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/moka-pot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/moka-pot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/moka-pot.svg"/>
                        Moka Pot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/nitrous.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/nitrous.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/nitrous.svg"/>
                        Nitrous
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/percolator.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/percolator.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/percolator.svg"/>
                        Percolator
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/porcelain-teapot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/porcelain-teapot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/porcelain-teapot.svg"/>
                        Porcelain Teapot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/portafilter.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/portafilter.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/portafilter.svg"/>
                        Portafilter
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/sack-of-coffee-beans.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/sack-of-coffee-beans.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/sack-of-coffee-beans.svg"/>
                        Sack of Coffee Beans
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/sugar-and-milk.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/sugar-and-milk.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/sugar-and-milk.svg"/>
                        Sugar and Milk
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/sugar.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/sugar.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/sugar.svg"/>
                        Sugar
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/syphon.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/syphon.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/syphon.svg"/>
                        Syphon
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/take-away.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/take-away.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/take-away.svg"/>
                        Take Away
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/tea-bag-cup.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/tea-bag-cup.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/tea-bag-cup.svg"/>
                        Tea Bag Cup
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/tea-bag.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/tea-bag.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/tea-bag.svg"/>
                        Tea Bag
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/tea-ceremony.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/tea-ceremony.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/tea-ceremony.svg"/>
                        Tea Ceremony
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/turkish-gezve.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/turkish-gezve.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/turkish-gezve.svg"/>
                        Turkish Gezve
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/vacuum-pot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/vacuum-pot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/vacuum-pot.svg"/>
                        Vacuum Pot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/vending-machine.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/vending-machine.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/vending-machine.svg"/>
                        Vending Machine
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/vietnamese-hot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/vietnamese-hot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/vietnamese-hot.svg"/>
                        Vietnamese Hot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/vintage-coffee-pot.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/vintage-coffee-pot.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/vintage-coffee-pot.svg"/>
                        Vintage Coffee Pot
                    </div>
                    <div class="large-3 medium-3 new-icon-container cell"
                         v-bind:class="{'active': icon === '/storage/img/brew_methods/wifi.svg'}"
                         v-on:click="selectIcon('/storage/img/brew_methods/wifi.svg')">
                        <img class="new-icon" src="/storage/img/brew_methods/wifi.svg"/>
                        Wifi
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {EventBus} from '../../event-bus.js';

    export default {
        data() {
            return {
                method: '',
                icon: '',
                showChangeIcon: false,
                validations: {
                    method: true
                }
            }
        },

        created() {
            this.$store.dispatch('loadAdminBrewMethod', {
                id: this.$route.params.id
            });
        },

        computed: {

            brewMethod() {
                return this.$store.getters.getAdminBrewMethod;
            },

            brewMethodLoadStatus() {
                return this.$store.getters.getAdminBrewMethodLoadStatus;
            },

            brewMethodUpdateStatus() {
                return this.$store.getters.getAdminBrewMethodUpdateStatus;
            }
        },

        watch: {

            'brewMethodLoadStatus': function () {
                if (this.brewMethodLoadStatus === 2) {
                    this.syncBrewMethodToModel();
                }
            },

            'brewMethodUpdateStatus': function () {
                if (this.brewMethodUpdateStatus === 2) {
                    this.syncBrewMethodToModel();
                    EventBus.$emit('show-success', {
                        notification: '冲泡方法更新成功!'
                    });
                }
            }
        },

        methods: {
            syncBrewMethodToModel() {
                this.method = this.brewMethod.method;
                this.icon = this.brewMethod.icon;
            },

            updateBrewMethod() {
                if (this.validateEditBrewMethod()) {
                    this.$store.dispatch('updateAdminBrewMethod', {
                        id: this.brewMethod.id,
                        method: this.method,
                        icon: this.icon
                    });
                }
            },

            validateEditBrewMethod() {
                let validEditBrewMethodForm = true;

                if (this.method.trim() === '') {
                    validEditBrewMethodForm = false;
                    this.validations.method = false;
                } else {
                    this.validations.method = true;
                }
                return validEditBrewMethodForm;
            },

            promptChangeIcon() {
                this.showChangeIcon = true;
            },

            hideChangeIcon() {
                this.showChangeIcon = false;
            },

            selectIcon(url) {
                this.icon = url;
                this.hideChangeIcon();
            }
        }
    }
</script>