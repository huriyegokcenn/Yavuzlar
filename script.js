const questions = [];

function soruGöster(filteredQuestions = questions) {
    const soruListesi = document.getElementById("soruListesi");
    soruListesi.innerHTML = "";

    filteredQuestions.forEach((questionData, index) => {
        const li = document.createElement("li");
        li.className = "question-item";
        li.innerHTML = `
            <span onclick="soruDetayaGit(${index})" style="cursor: pointer; color: black; text-decoration: none;">
                Soru ${index + 1}: ${questionData.question}
            </span>
            <ul style="display: none;">
                ${questionData.options.map((option, i) => `<li>Şık ${i + 1}: ${option}</li>`).join('')}
            </ul>
            <div>
                <button onclick="soruDüzenle(${index})">Düzenle</button>
                <button onclick="soruSil(${index})">Sil</button>
            </div>
        `;
        soruListesi.appendChild(li);
    });
}


function soruDüzenle(index) {
    const questionItem = document.querySelectorAll('.question-item')[index];
    const optionsList = questionItem.querySelector('ul');
    optionsList.style.display = 'block';
    inputlariGoster();

    const yeniSoru = prompt("Soruyu düzenleyin:", questions[index].question);
    if (yeniSoru !== null && yeniSoru.trim() !== "") {
        questions[index].question = yeniSoru;


        for (let i = 0; i < 4; i++) {
            const yeniŞık = prompt(`Şık ${i + 1} için yeni değeri girin:`, questions[index].options[i]);
            if (yeniŞık !== null && yeniŞık.trim() !== "") {
                questions[index].options[i] = yeniŞık;
            }
        }
        localStorage.setItem('questions', JSON.stringify(questions));
        soruGöster();
    }
}


function soruEkle() {
    const soruGirişi = document.getElementById("soruGirişi").value.trim();
    const şık1 = document.getElementById("şık1").value.trim();
    const şık2 = document.getElementById("şık2").value.trim();
    const şık3 = document.getElementById("şık3").value.trim();
    const şık4 = document.getElementById("şık4").value.trim();

    if (soruGirişi !== "" && şık1 !== "" && şık2 !== "" && şık3 !== "" && şık4 !== "") {
        const yeniSoru = {
            question: soruGirişi,
            options: [şık1, şık2, şık3, şık4]
        };

        questions.push(yeniSoru);


        localStorage.setItem('questions', JSON.stringify(questions));


        document.getElementById("soruGirişi").value = "";
        document.getElementById("şık1").value = "";
        document.getElementById("şık2").value = "";
        document.getElementById("şık3").value = "";
        document.getElementById("şık4").value = "";
        soruGöster();
    } else {
        alert("Lütfen tüm alanları doldurun.");
    }
}



window.onload = function () {
    const storedQuestions = JSON.parse(localStorage.getItem('questions'));
    if (storedQuestions) {
        questions.push(...storedQuestions);
    }
    soruGöster();
}



function soruSil(index) {
    questions.splice(index, 1);


    localStorage.setItem('questions', JSON.stringify(questions));

    soruGöster();
}


function soruDetayaGit(index) {

    localStorage.setItem('selectedQuestion', JSON.stringify(questions[index]));

    window.location.href = 'soruDetay.html';
}



function soruAra() {
    const ara = document.getElementById("soruAra").value.toLowerCase();
    const arananSoru = questions.filter(q => q.question.toLowerCase().includes(ara));
    soruGöster(arananSoru);
}


function soruKaydet() {
    const soruGirişi = document.getElementById("soruGirişi").value.trim();
    const şık1 = document.getElementById("şık1").value.trim();
    const şık2 = document.getElementById("şık2").value.trim();
    const şık3 = document.getElementById("şık3").value.trim();
    const şık4 = document.getElementById("şık4").value.trim();

    if (soruGirişi !== "" && şık1 !== "" && şık2 !== "" && şık3 !== "" && şık4 !== "") {
        const yeniSoru = {
            question: soruGirişi,
            options: [şık1, şık2, şık3, şık4]
        };

        questions.push(yeniSoru);


        localStorage.setItem('questions', JSON.stringify(questions));

        // Input alanlarını temizle
        document.getElementById("soruGirişi").value = "";
        document.getElementById("şık1").value = "";
        document.getElementById("şık2").value = "";
        document.getElementById("şık3").value = "";
        document.getElementById("şık4").value = "";
        soruGöster();
    } else {
        alert("Lütfen tüm alanları doldurun.");
    }
}


soruGöster();
