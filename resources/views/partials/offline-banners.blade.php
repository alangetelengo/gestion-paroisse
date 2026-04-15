<div id="offline-banner" class="offline-banner" style="display: none;">
    <div class="d-flex align-items-center justify-content-center gap-2 py-2 px-3" style="background: #ff9800; color: #fff; font-weight: 600;">
        <i class="fas fa-wifi" style="opacity: 0.7;"></i>
        <span>Mode hors ligne — Les données seront synchronisées automatiquement</span>
        <span id="offline-pending-count" class="badge bg-dark ms-1">0</span>
    </div>
</div>
<div id="offline-pending-banner" class="offline-banner" style="display: none;">
    <div class="d-flex align-items-center justify-content-between gap-2 py-2 px-3" style="background: #2196f3; color: #fff;">
        <span><i class="fas fa-cloud-upload-alt me-1"></i> <span id="offline-pending-text">0</span> élément(s) en attente de synchronisation</span>
        <button type="button" class="btn btn-sm btn-light" onclick="if(window.OfflineSync) window.OfflineSync.syncNow()">
            <i class="fas fa-sync-alt me-1"></i> Synchroniser
        </button>
    </div>
</div>
