<style>
    /* Tombol Pemicu AI di Kanan Bawah */
    .ai-fab {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 60px;
        height: 60px;
        background: linear-gradient(135deg, #E8521A, #b3390f);
        border-radius: 50%;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
        box-shadow: 0 4px 15px rgba(232, 82, 26, 0.4);
        z-index: 9999;
        transition: transform 0.2s cubic-bezier(0.175, 0.885, 0.32, 1.275);
    }
    .ai-fab:hover { transform: scale(1.15) rotate(-10deg); }
    
    /* Overlay Transparan & Container Pop-Up Tengah Layar */
    .ai-modal-overlay {
        position: fixed; top: 0; left: 0; width: 100vw; height: 100vh;
        background: rgba(10, 10, 8, 0.85); backdrop-filter: blur(5px);
        z-index: 10000;
        display: none; /* Disembunyikan awalnya */
        align-items: center; justify-content: center;
    }
    .ai-modal-overlay.show { display: flex; animation: fadeIn 0.2s ease-out forwards; }
    @keyframes fadeIn { from { opacity:0; } to { opacity:1; } }
    
    .ai-modal-content {
        background: #111110; border: 1px solid rgba(232, 82, 26, 0.3);
        width: 90%; max-width: 500px; height: 65vh; border-radius: 8px;
        display: flex; flex-direction: column; overflow: hidden;
        box-shadow: 0 20px 50px rgba(0,0,0,0.8);
    }
    .ai-modal-header {
        padding: 1.2rem; background: #151513; border-bottom: 1px solid rgba(242,239,230,0.08);
        display: flex; justify-content: space-between; align-items: center;
    }
    .ai-modal-body {
        flex: 1; padding: 1.5rem; overflow-y: auto; background: #0c0c0b;
    }
    .ai-modal-footer {
        padding: 1rem; border-top: 1px solid rgba(242,239,230,0.08); display: flex; gap: 0.5rem; background: #111110;
    }
    .ai-modal-footer input {
        flex: 1; padding: 0.85rem; background: #1A1A16; border: 1px solid rgba(242,239,230,0.1);
        color: #F2EFE6; border-radius: 4px; outline: none; font-family: inherit;
    }
    .ai-modal-footer input:focus { border-color: #E8521A; }
    
    /* Balon Pesan */
    .ai-msg { margin-bottom: 1.2rem; padding: 0.85rem 1.2rem; border-radius: 8px; width: fit-content; max-width: 85%; font-size: 0.9rem; line-height: 1.5; color: #F2EFE6; }
    .ai-msg.bot { background: rgba(242,239,230,0.05); border-bottom-left-radius: 2px; border: 1px solid rgba(242,239,230,0.1); }
    .ai-msg.user { background: rgba(232, 82, 26, 0.15); margin-left: auto; border-bottom-right-radius: 2px; border: 1px solid rgba(232, 82, 26, 0.3); }
</style>

<!-- Trigger Kanan Bawah -->
<div class="ai-fab" onclick="document.getElementById('aiModal').classList.add('show')">
    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2.5"><path d="M21 11.5a8.38 8.38 0 0 1-.9 3.8 8.5 8.5 0 0 1-7.6 4.7 8.38 8.38 0 0 1-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 0 1-.9-3.8 8.5 8.5 0 0 1 4.7-7.6 8.38 8.38 0 0 1 3.8-.9h.5a8.48 8.48 0 0 1 8 8v.5z"/></svg>
</div>

<!-- Modal Berada di TENGAH LAYAR -->
<div class="ai-modal-overlay" id="aiModal">
    <div class="ai-modal-content">
        <div class="ai-modal-header">
            <div style="font-weight:bold; color:#E8521A; letter-spacing: 0.05em; display:flex; align-items:center; gap:0.5rem;">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="10" rx="2"/><circle cx="12" cy="5" r="2"/><path d="M12 7v4"/><line x1="8" y1="16" x2="8" y2="16"/><line x1="16" y1="16" x2="16" y2="16"/></svg>
                Mekanik AI Pro
            </div>
            <button onclick="document.getElementById('aiModal').classList.remove('show')" style="background:transparent; border:none; color:#888880; cursor:pointer; font-size:1.5rem; transition:0.2s;">&times;</button>
        </div>
        
        <div class="ai-modal-body" id="aiChatBody">
            <div class="ai-msg bot">Halo Bro! Saya Mekanik AI dari Partlyfe. Motormu lagi ada masalah apa nih?</div>
        </div>
        
        <div class="ai-modal-footer">
            <input type="text" id="aiInput" placeholder="Ketik keluhan motormu..." onkeypress="if(event.key === 'Enter') sendAiMsgHardcoded()">
            <button onclick="sendAiMsgHardcoded()" style="background:#E8521A; color:white; border:none; padding:0 1.5rem; border-radius:4px; cursor:pointer; font-weight:bold;">Kirim</button>
        </div>
    </div>
</div>

<script>
    function sendAiMsgHardcoded() {
        let input = document.getElementById('aiInput');
        let val = input.value.trim();
        if(!val) return;
        
        document.getElementById('aiChatBody').innerHTML += `<div class="ai-msg user">${val}</div>`;
        input.value = '';
        
        // Delay efek membalas
        setTimeout(() => { document.getElementById('aiChatBody').innerHTML += `<div class="ai-msg bot">Sistem saya sedang mode Hardcoded UI, namun ini saran saya: Periksa komponen sistem pengereman, CVT, atau busi motormu. Jangan lupa rawat di Partlyfe ya! 🔧</div>`; 
        document.getElementById('aiChatBody').scrollTop = document.getElementById('aiChatBody').scrollHeight; }, 1000);
    }
</script>