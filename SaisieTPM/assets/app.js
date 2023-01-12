/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.scss';

// start the Stimulus application
import './bootstrap';

import 'tailwindcss/tailwind.css';

import 'tw-elements';

import Swal from 'sweetalert2';

document.addEventListener('DOMContentLoaded', async () => {
    new App();
});

class App {
    /**
     * @type {HTMLButtonElement}
     */
    btnDeconnexion

    /**
     * @type {HTMLButtonElement}
     */
    btnMesForm

    /**
     * @type {HTMLButtonElement}
     */
    btnPanelAdmin

    /**
     * @type {HTMLButtonElement}
     */
    btnConnexion

    constructor() {
        this.btnDeconnexion = document.querySelector("#btnDeconnexion");
        this.btnMesForm = document.querySelector("#btnMesForm");
        this.btnPanelAdmin = document.querySelector("#btnPanelAdmin");
        this.btnConnexion = document.querySelector("#btnConnexion");

        if(this.btnDeconnexion) {
            this.btnDeconnexion.addEventListener('click', async () => {
                await this.MessageDeconnexion();
            })
        }

        if(this.btnConnexion) {
            this.btnConnexion.addEventListener('click', async () => {
                await this.MessageConnexion();
            })
        }

        if(this.btnMesForm) {
            this.btnMesForm.addEventListener('click', async () => {
                await this.MessageMesForm();
            })
        }

        if (this.btnPanelAdmin) {
            this.btnPanelAdmin.addEventListener('click', async () => {
                await this.MessagePanelAdmin();
            })
        }
    }

    async MessageDeconnexion() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
        })

        Toast.fire({
            icon: 'success',
            title: 'Déconnexion effectuée avec succès'
        })
    }

    async MessageConnexion() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
        })

        Toast.fire({
            icon: 'success',
            title: 'Connexion effectuée avec succès'
        })
    }

    async MessageMesForm() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
        })

        Toast.fire({
            icon: 'success',
            title: 'Espace contenant vos formulaires'
        })
    }

    async MessagePanelAdmin() {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: false,
        })

        Toast.fire({
            icon: 'success',
            title: "Bienvenue sur le panel d'administration"
        })
    }
}