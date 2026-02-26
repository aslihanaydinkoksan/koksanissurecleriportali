<style>
    /* --- ORİJİNAL STİLLER (KORUNDU) --- */
    #app>main.py-4 { padding: 2.5rem 0 !important; min-height: calc(100vh - 72px); background: linear-gradient(-45deg, #dbe4ff, #fde2ff, #d9fcf7, #fff0d9); background-size: 400% 400%; animation: gradientWave 18s ease infinite; }
    @keyframes gradientWave { 0% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } 100% { background-position: 0% 50%; } }
    .customer-card { background-color: rgba(255, 255, 255, 0.95); border-radius: 1rem; box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08); border: none; backdrop-filter: blur(10px); overflow: hidden; }
    /* --- YENİ SIDEBAR YAPISI --- */
    .sidebar-wrapper { background-color: rgba(248, 250, 252, 0.6); border-right: 1px solid rgba(102, 126, 234, 0.1); height: 100%; padding: 1.5rem 1rem; }
    .sidebar-category-title { font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.08em; color: #94a3b8; font-weight: 800; margin-top: 1.5rem; margin-bottom: 0.75rem; padding-left: 1rem; }
    .sidebar-category-title:first-child { margin-top: 0; }
    .nav-pills .nav-link { border-radius: 0.75rem; font-weight: 600; color: #64748b; padding: 0.8rem 1rem; transition: all 0.2s ease; text-align: left; display: flex; align-items: center; justify-content: space-between; margin-bottom: 0.25rem; }
    .nav-pills .nav-link i { width: 24px; font-size: 1.1em; margin-right: 0.5rem; text-align: center; }
    .nav-pills .nav-link:hover { background-color: rgba(102, 126, 234, 0.1); color: #4f46e5; transform: translateX(4px); }
    .nav-pills .nav-link.active { background: linear-gradient(135deg, #667EEA 0%, #764BA2 100%); color: white !important; box-shadow: 0 4px 12px rgba(102, 126, 234, 0.3); }
    .nav-pills .nav-link.active i { color: white !important; }
    .nav-pills .nav-link.active .badge { background-color: rgba(255, 255, 255, 0.25) !important; color: white !important; border-color: transparent !important; }
    /* Sağ İçerik Alanı */
    .tab-content-area { padding: 2rem; min-height: 800px; }
    /* --- ORİJİNAL FORM VE TABLO STİLLERİ --- */
    .quick-add-form { background-color: rgba(255, 255, 255, 0.7); border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1.5rem; border: 2px dashed rgba(102, 126, 234, 0.2); }
    .quick-add-form .form-label { font-weight: 600; color: #495057; margin-bottom: 0.5rem; }
    .quick-add-form .form-control, .quick-add-form .form-select { border: 2px solid rgba(102, 126, 234, 0.2); border-radius: 0.5rem; transition: all 0.3s ease; background-color: rgba(255, 255, 255, 0.9); }
    .quick-add-form .form-control:focus, .quick-add-form .form-select:focus { border-color: #667EEA; box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.15); }
    .pulsing { animation: pulse-red 1.5s infinite; }
    @keyframes pulse-red { 0% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.7); } 70% { box-shadow: 0 0 0 10px rgba(220, 53, 69, 0); } 100% { box-shadow: 0 0 0 0 rgba(220, 53, 69, 0); } }
    .btn-animated-gradient { background: linear-gradient(-45deg, #667EEA, #F093FB, #4FD1C5, #FBD38D); background-size: 400% 400%; animation: gradientWave 18s ease infinite; border: none; color: white; font-weight: bold; transition: transform 0.2s ease-out, box-shadow 0.2s ease-out; box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3); }
    .btn-animated-gradient:hover { color: white; transform: translateY(-2px); box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4); }
    .table { background-color: transparent; }
    .table thead th { background-color: rgba(102, 126, 234, 0.08); border-bottom: 2px solid #667EEA; }
    .filter-bar { background-color: rgba(245, 247, 250, 0.7); border: 1px solid rgba(102, 126, 234, 0.1); }
    .status-select { font-size: 0.85rem; padding: 0.25rem 0.5rem; border-radius: 0.5rem; font-weight: 600; cursor: pointer; border: 1px solid transparent; }
    .status-select.status-planlandi, .status-select.status-pending, .status-select.status-open, .status-select.status-preparing { color: #d97706; background-color: #fffbeb; border-color: #fcd34d; }
    .status-select.status-gerceklesti, .status-select.status-approved, .status-select.status-resolved, .status-select.status-delivered { color: #059669; background-color: #ecfdf5; border-color: #6ee7b7; }
    .status-select.status-iptal, .status-select.status-rejected { color: #dc2626; background-color: #fef2f2; border-color: #fca5a5; }
    .status-select.status-ertelendi, .status-select.status-completed, .status-select.status-in_progress, .status-select.status-sent { color: #0891b2; background-color: #ecfeff; border-color: #67e8f9; }
    .opp-card { transition: all 0.3s ease; border-left: 5px solid transparent; }
    .opp-card:hover { transform: translateY(-3px); box-shadow: 0 10px 20px rgba(0, 0, 0, 0.08) !important; }
    .opp-duyum { border-left-color: #6c757d; }
    .opp-teklif { border-left-color: #f59e0b; }
    .opp-gorusme { border-left-color: #3b82f6; }
    .opp-kazanildi { border-left-color: #10b981; }
    .opp-kaybedildi { border-left-color: #ef4444; opacity: 0.7; }
</style><?php /**PATH C:\xampp\htdocs\koksanissurecleriportali-main\resources\views/customers/partials/styles.blade.php ENDPATH**/ ?>