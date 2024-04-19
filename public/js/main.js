
(() => {
    const states = document.querySelectorAll(".estado");
    const updateButton = document.querySelector(".btn-update");
  
    for (let state of states) {
      state.addEventListener("mouseover", function (e) {
        e.stopPropagation();
  
        const state = e.target.parentNode;
  
        const stateStatus = {
          uf: state.getAttribute("uf"),
          nome: state.getAttribute("nome"),
          codigoStatus: state.getAttribute("data-codigo-status"),
          descricaoStatus: state.getAttribute("data-descricao-status"),
          tempoResposta: state.getAttribute("data-tempo-resposta"),
          contingencia: state.getAttribute("data-contigencia"),
        };
  
        const container = document.querySelector(".container");
  
        removeCardIfExist();
        const card = createCard(stateStatus);
        container.appendChild(card);
      });
    }
  
    updateButton.addEventListener("click", () => {
      update();
    });
  
    getStatus();
})();

function removeCardIfExist() {
  document.querySelectorAll(".card").forEach((e) => e.remove());
}

function createCard(stateStatus) {
  const card = document.createElement("div");
  card.classList.add("card");

  const h2 = document.createElement("h2");
  h2.textContent = `${stateStatus.uf} - ${stateStatus.nome}`;

  const info = document.createElement("div");
  info.classList.add("card-info");

  info.innerHTML = `            
            <p><b>Status</b>: ${stateStatus.codigoStatus} - ${stateStatus.descricaoStatus}</p>
            <p><b>Tempo de Resposta</b>: ${stateStatus.tempoResposta}s</p>
            <p><b>Em contingência</b>: ${stateStatus.contingencia == 'true' ? 'Sim' : 'Não'}</p>
        `;

  card.appendChild(h2);
  card.appendChild(info);

  return card;
}

function update() {
  return request("POST", "/update", function (response) {
    if (response.success) {
      dialog(response.message);
      getStatus();
    }
  });
}

function getStatus() {
  return request("GET", "/list", function (states) {
    
    if(states.length > 0) {
      const container = document.querySelector(".container");
      const legend = document.querySelector(".legend");

      container.style.visibility = 'visible';
      legend.style.visibility = 'visible';
    }

    states.forEach((state) => {
      const stateElement = document.querySelector(`a[uf="${state.state}"]`);

      if (stateElement !== null) {
        stateElement.setAttribute("data-codigo-status", state.webserviceCode);
        stateElement.setAttribute(
          "data-descricao-status",
          state.description
        );
        stateElement.setAttribute(
          "data-tempo-resposta",
          state.averageResponseTime
        );
        
        stateElement.setAttribute(
          "data-contigencia",
          state.contigency
        );

        stateElement.classList.remove('normal');
        stateElement.classList.remove('instable');
        stateElement.classList.remove('stopped');
    
        stateElement.classList.add(state.statusDescription);
      }
    });
  });
}

function request(method, url, callback, body = null) {
  fetch(url, {
    method: method,
    headers: {
      Accept: "application/json",
      "Content-Type": "application/json",
    },
    body: body ? JSON.stringify(body) : null,
  })
    .then((response) => {
      if (!response.ok) {
        throw new Error("Não realizar a requisição");
      }

      return response;
    })
    .then((response) => response.json())
    .then((data) => {
      if (callback) {
        callback(data);
      }
    })
    .catch((error) => {
      dialog(error.message);
    });
}

function dialog(message) {
  const dialog = document.createElement("dialog");
  dialog.className = "modal";

  const p = document.createElement("p");
  p.textContent = message;

  const closeButton = document.createElement("button");
  closeButton.className = "close__button";
  closeButton.appendChild(document.createTextNode("OK"));
  closeButton.setAttribute("autofocus", true);
  closeButton.addEventListener("click", () => {
    dialog.close();
  });

  dialog.appendChild(p);
  dialog.appendChild(closeButton);
  document.body.appendChild(dialog);

  dialog.showModal();
}