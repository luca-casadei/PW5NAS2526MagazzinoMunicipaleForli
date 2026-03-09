"use strict";
import { ApiRequest } from '/components/global/ApiRequest.js';
document.addEventListener("DOMContentLoaded", async ()=>{
    const logoutbtn = document.getElementById("logoutBtn");
    logoutbtn.addEventListener("click", async()=>{
        try {
            const responseJSON = await ApiRequest.request('logout_service.php', 'DELETE', null);
            if (responseJSON.status === "success"){
                window.location.href="/index.php";
            }
            else{
                console.log(responseJSON.message);
                alert(responseJSON.message)
            }
        } catch (error) {
            alert("Impossibile effettuare il logout. Errore: " + error.message);
            console.error("Dettagli errore logout:", error);
        }
    });
})