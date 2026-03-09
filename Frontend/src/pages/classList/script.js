"use strict"
import { ApiRequest } from '/components/global/ApiRequest.js';

document.addEventListener("DOMContentLoaded", ()=>{
    const filter = document.getElementById("typeClassSelect");
    const text_input = document.getElementById("text_input");
    const errorMessageElement = document.getElementById("error_message"); 
    
    const attempt_getUserClasses = async () => {
        try {
            const responseJSON = await ApiRequest.request('userClasse_service.php', 'GET', null);

            if (!responseJSON) {
                errorMessageElement.innerText = "Errore di connessione al server.";
                return []; 
            }

            if (responseJSON.status === "success"){
                return responseJSON.body;
            } else {
                console.log(responseJSON.message);
                errorMessageElement.innerText = responseJSON.message;
                return []; 
            }
        } catch (error) {
            errorMessageElement.innerText = "Error when program tried to get the classes: " + error.message;
            console.error(error);
            return []; 
        }
    }

    const attempt_getClassesAsProfessor = async () => {
        try {
            const responseJSON = await ApiRequest.request('professorClasses_service.php', 'GET', null);

            if (!responseJSON) {
                errorMessageElement.innerText = "Errore di connessione al server.";
                return [];
            }

            if (responseJSON.status === "success"){
                return responseJSON.body;
            } else {
                console.log(responseJSON.message);
                errorMessageElement.innerText = responseJSON.message;
                return [];
            }
        } catch (error) {
            errorMessageElement.innerText = "Error when program tried to get the classes: " + error.message;
            console.error(error);
            return []; 
        }
    }

    const attempt_visualizeResult = async (data) => {
        const classList = document.getElementById("classList");
        classList.innerHTML = "";
        
        if (!Array.isArray(data)) return; // Ferma tutto se data non è un array

        data.forEach(classe => {
            const li = document.createElement("li");
            const h3 = document.createElement("h3");
            h3.innerText = classe.nome;
            const div = document.createElement("div");
            const p = document.createElement("p");
            
            p.innerHTML = "Link: " + classe.link;
            p.innerHTML += "<br>Subject: " + classe.materia;
            p.innerHTML += "<br>Professor's Email: " + classe.respoEmail;
            p.innerHTML += "<br>Professor's Name: " + classe.respoFullName;
            
            div.appendChild(p);
            li.appendChild(h3);
            li.appendChild(div);
            classList.appendChild(li);
        });
    }

    const attempt_loadClasses = async () => {
        errorMessageElement.innerText = ""; 

        const type = filter.value;
        if(type === "classesAsRespo"){
            const data = await attempt_getClassesAsProfessor();
            attempt_visualizeResult(data);
        } else {
            const data = await attempt_getUserClasses();
            attempt_visualizeResult(data);
        }
    }
    
    filter.addEventListener("change", async () => {
        let selectedText = "";
        for (let i = 0; i < filter.children.length; i++) {
            if (filter.children[i].value == filter.value) {
                selectedText = filter.children[i].textContent;
                break; 
            }
        }
        text_input.textContent = `Classes as ${selectedText}:`;
        attempt_loadClasses();
    });

    attempt_loadClasses();
});