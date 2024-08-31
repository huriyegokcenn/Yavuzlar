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
                ${questionData.options.map((option, i) => `
                    <li>Şık ${i + 1}: ${option} ${questionData.correctAnswer === i ? '(Doğru Cevap)' : ''}</li>
                `).join('')}
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
    const storedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
    const question = storedQuestions[index];

    document.getElementById('soruGirişi').value = question.question;
    document.getElementById('şık1').value = question.options[0];
    document.getElementById('şık2').value = question.options[1];
    document.getElementById('şık3').value = question.options[2];
    document.getElementById('şık4').value = question.options[3];
    document.getElementById('doğruCevap').value = question.correctAnswer;
    document.getElementById('zorlukDerecesi').value = question.difficulty; // Zorluk derecesini düzenleme

    document.getElementById('inputContainer').style.display = 'block';

    document.getElementById('soruGirişi').dataset.index = index;
}




function soruKaydet() {
    const soruMetni = document.getElementById('soruGirişi').value;
    const şık1 = document.getElementById('şık1').value;
    const şık2 = document.getElementById('şık2').value;
    const şık3 = document.getElementById('şık3').value;
    const şık4 = document.getElementById('şık4').value;
    const doğruCevap = parseInt(document.getElementById('doğruCevap').value);
    const zorlukDerecesi = document.getElementById('zorlukDerecesi').value; // Zorluk derecesi alınması

    if (!soruMetni || !şık1 || !şık2 || !şık3 || !şık4 || isNaN(doğruCevap) || !zorlukDerecesi) {
        alert('Lütfen tüm alanları doldurun ve doğru cevabı ve zorluk derecesini seçin.');
        return;
    }

    const yeniSoru = {
        question: soruMetni,
        options: [şık1, şık2, şık3, şık4],
        correctAnswer: doğruCevap,
        difficulty: zorlukDerecesi
    };

    const storedQuestions = JSON.parse(localStorage.getItem('questions')) || [];
    const index = document.getElementById('soruGirişi').dataset.index;

    if (index) {
        
        storedQuestions[index] = yeniSoru;
    } else {
        storedQuestions.push(yeniSoru);
    }

    localStorage.setItem('questions', JSON.stringify(storedQuestions));

    alert('Soru başarıyla kaydedildi!');
    document.getElementById('inputContainer').style.display = 'none';
    document.getElementById('soruGirişi').value = '';
    document.getElementById('şık1').value = '';
    document.getElementById('şık2').value = '';
    document.getElementById('şık3').value = '';
    document.getElementById('şık4').value = '';
    document.getElementById('doğruCevap').value = '';
    document.getElementById('zorlukDerecesi').value = '';
    soruGöster();
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







soruGöster();
