
<script>
    document.addEventListener("DOMContentLoaded", function() {
        function filterList(dateInputId, searchInputId, statusInputId, itemSelector) {
            const dateVal = document.getElementById(dateInputId)?.value || '';
            const searchVal = document.getElementById(searchInputId)?.value.toLowerCase().trim() || '';
            const statusVal = document.getElementById(statusInputId)?.value || '';

            const items = document.querySelectorAll(itemSelector);

            items.forEach(item => {
                if (item.classList.contains('empty-message-row')) return;
                const itemDate = item.getAttribute('data-date') || '';
                const itemSearch = item.getAttribute('data-search') || '';
                const itemStatus = item.getAttribute('data-status') || '';

                const matchDate = !dateVal || itemDate === dateVal;
                const matchSearch = !searchVal || itemSearch.includes(searchVal);
                const matchStatus = !statusVal || itemStatus === statusVal || (statusVal ===
                    'pending' && ['pending', 'open', 'preparing'].includes(itemStatus));

                if (matchDate && matchSearch && matchStatus) {
                    item.style.display = '';
                } else {
                    item.style.display = 'none';
                }
            });
        }

        function attachFilters(prefix, itemSelector) {
            const dateEl = document.getElementById(`filter${prefix}Date`);
            const searchEl = document.getElementById(`filter${prefix}Search`);
            const statusEl = document.getElementById(`filter${prefix}Status`);

            const trigger = () => filterList(`filter${prefix}Date`, `filter${prefix}Search`,
                `filter${prefix}Status`, itemSelector);

            if (dateEl) dateEl.addEventListener('input', trigger);
            if (searchEl) searchEl.addEventListener('input', trigger);
            if (statusEl) statusEl.addEventListener('change', trigger);
        }

        attachFilters('Prod', '.product-item');
        attachFilters('Opp', '.opp-item');
        attachFilters('Act', '.activity-item');
        attachFilters('Vis', '.visit-item');
        attachFilters('Sam', '.sample-item');
        attachFilters('Ret', '.return-item');
        attachFilters('Comp', '.complaint-item');
        attachFilters('Mac', '.machine-item');
        attachFilters('Test', '.test-item');
        attachFilters('Log', '.logistic-item');
    });

    // DİNAMİK SEVKİYAT TÜRÜ GEÇİŞİ (Numune / Ürün)
    function toggleShipmentType(type, prefix) {
        const productWrapper = document.getElementById(prefix + 'wrapper_product');
        const sampleWrapper = document.getElementById(prefix + 'wrapper_sample');
        const productSelect = document.getElementById(prefix + 'product_select');
        const sampleSelect = document.getElementById(prefix + 'sample_select');

        if (type === 'sample') {
            // Numuneyi Göster, Ürünü Gizle
            if (productWrapper) productWrapper.style.display = 'none';
            if (sampleWrapper) sampleWrapper.style.display = 'block';
            if (productSelect) productSelect.value = ""; // Gizlenenin verisini sıfırla
        } else {
            // Ürünü Göster, Numuneyi Gizle
            if (sampleWrapper) sampleWrapper.style.display = 'none';
            if (productWrapper) productWrapper.style.display = 'block';
            if (sampleSelect) sampleSelect.value = ""; // Gizlenenin verisini sıfırla
        }
    }
</script>


<script>
    function toggleVoiceInput(inputId, buttonId) {
        if (!('webkitSpeechRecognition' in window) && !('SpeechRecognition' in window)) {
            alert("Tarayıcınız sesli yazdırmayı desteklemiyor. Lütfen Google Chrome kullanın.");
            return;
        }

        const recognition = new(window.SpeechRecognition || window.webkitSpeechRecognition)();
        const inputField = document.getElementById(inputId);
        const btnIcon = document.querySelector(`#${buttonId} i`);
        const btnElement = document.getElementById(buttonId);

        recognition.lang = 'tr-TR';
        recognition.continuous = false;
        recognition.interimResults = false;

        recognition.onstart = function() {
            if (btnIcon) {
                btnIcon.classList.remove('fa-microphone');
                btnIcon.classList.add('fa-spinner', 'fa-spin');
            }
            if (btnElement) {
                btnElement.classList.remove('btn-outline-secondary', 'btn-secondary');
                btnElement.classList.add('btn-danger', 'pulsing');
            }
        };

        recognition.onresult = function(event) {
            const transcript = event.results[0][0].transcript;
            if (inputField.value.trim() === "") {
                inputField.value = transcript.charAt(0).toUpperCase() + transcript.slice(1);
            } else {
                inputField.value += " " + transcript;
            }
        };

        recognition.onend = function() {
            resetButton(btnIcon, btnElement);
        };
        recognition.onerror = function(event) {
            console.error("Ses tanıma hatası:", event.error);
            resetButton(btnIcon, btnElement);
        };

        recognition.start();
    }

    function resetButton(icon, btn) {
        if (icon) {
            icon.classList.remove('fa-spinner', 'fa-spin');
            icon.classList.add('fa-microphone');
        }
        if (btn) {
            btn.classList.remove('btn-danger', 'pulsing');
            btn.classList.add('btn-outline-secondary');
        }
    }
</script>


<script>
    document.addEventListener("DOMContentLoaded", function() {
        // 1. Müşteriye özel benzersiz hafıza anahtarını oluştur
        const storageKey = 'lastActiveCustomerTab_' + window.location.pathname;
        const activeTabId = localStorage.getItem(storageKey);

        if (activeTabId) {
            const tabElement = document.getElementById(activeTabId);
            if (tabElement) {
                // Global bootstrap objesine güvenmek yerine native (doğal) tıklama tetikliyoruz.
                // Bu yöntem %100 tüm tarayıcılarda ve yapılarda çalışır.
                tabElement.click();
            }
        }

        // 2. Kullanıcı sekmeye tıkladığında ID'yi hafızaya kaydet
        const tabLinks = document.querySelectorAll('button[data-bs-toggle="pill"]');
        tabLinks.forEach(function(tabLink) {
            tabLink.addEventListener('shown.bs.tab', function(event) {
                localStorage.setItem(storageKey, event.target.id);
            });
        });
    });
</script>


<script>
    // Rakip radyo butonu seçilince ekstra alanları göster/gizle
    function toggleCompetitorFields(radioId, prefix) {
        const isCompetitor = document.getElementById(radioId).value === 'competitor';
        const panel = document.getElementById(prefix + 'competitor_panel');
        const compSelect = document.getElementById(prefix + 'competitor_id');

        if (isCompetitor) {
            panel.style.display = 'block';
            compSelect.setAttribute('required', 'required');
        } else {
            panel.style.display = 'none';
            compSelect.removeAttribute('required');
        }
    }

    // Teknik Özellik Satırı Ekle
    function addSpecRow(containerId) {
        const container = document.getElementById(containerId);
        const row = document.createElement('div');
        row.className = 'row mb-2 spec-row';
        row.innerHTML = `
            <div class="col-5">
                <input type="text" name="spec_keys[]" class="form-control form-control-sm" placeholder="Özellik Adı (Örn: Mikron)">
            </div>
            <div class="col-6">
                <input type="text" name="spec_values[]" class="form-control form-control-sm" placeholder="Değer (Örn: 140)">
            </div>
            <div class="col-1">
                <button type="button" class="btn btn-sm btn-outline-danger w-100" onclick="removeSpecRow(this)"><i class="fa-solid fa-times"></i></button>
            </div>
        `;
        container.appendChild(row);
    }

    // Teknik Özellik Satırı Sil
    function removeSpecRow(button) {
        button.closest('.spec-row').remove();
    }

    // Bootstrap Popover (Teknik özellik tablosunu hover/click ile göstermek için)
    document.addEventListener("DOMContentLoaded", function() {
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'))
        var popoverList = popoverTriggerList.map(function(popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl)
        });
    });
</script>
<script>
    function toggleLossReason(selectId, wrapperId) {
        const stage = document.getElementById(selectId).value;
        const wrapper = document.getElementById(wrapperId);
        const input = wrapper.querySelector('input');

        // Eğer senin db değerin 'lost' ise 'kaybedildi' kelimesini 'lost' yap
        if (stage === 'kaybedildi' || stage === 'lost') {
            wrapper.style.display = 'block';
            input.setAttribute('required', 'required');
        } else {
            wrapper.style.display = 'none';
            input.removeAttribute('required');
            input.value = ''; // Farklı aşamaya geçilirse kayıp nedenini temizle
        }
    }
</script>
<?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/partials/scripts.blade.php ENDPATH**/ ?>