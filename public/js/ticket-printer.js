/**
 * SYSTÈME D'IMPRESSION DE TICKETS THERMIQUES
 * Compatible Chrome/Edge/Firefox
 * Version corrigée pour imprimantes USB via drivers système
 */

class TicketPrinter {
    constructor() {
        this.printerIP = null;
        this.printerPort = 9100;
        this.method = 'browser';
        this.autoCloseDelay = 2000; // Délai avant fermeture auto (ms)
        
        console.log('✅ TicketPrinter initialisé');
    }

    /**
     * MÉTHODE PRINCIPALE : Impression automatique
     */
    async print(venteId, method = 'browser') {
        console.log(`🎯 Lancement impression ticket ${venteId} (méthode: ${method})`);
        
        switch(method) {
            case 'direct':
                return await this.printDirect(venteId);
            case 'silent':
                return await this.printSilent(venteId);
            case 'browser':
            default:
                return await this.printBrowser(venteId);
        }
    }

    /**
     * MÉTHODE 1 : Impression directe (iframe invisible + auto-print)
     * ✅ Fonctionne sans popup et imprime automatiquement
     */
    async printDirect(venteId) {
        return new Promise((resolve, reject) => {
            try {
                console.log(`🖨️ Impression directe du ticket ${venteId}`);
                
                // Créer un iframe invisible
                const iframe = document.createElement('iframe');
                iframe.style.position = 'fixed';
                iframe.style.right = '0';
                iframe.style.bottom = '0';
                iframe.style.width = '0';
                iframe.style.height = '0';
                iframe.style.border = 'none';
                
                // URL du ticket
                const ticketUrl = `/ticket/${venteId}/imprimer?auto=1`;
                
                // Écouter le chargement
                iframe.onload = () => {
                    console.log('✅ Ticket chargé dans iframe');
                    
                    // Attendre un peu puis lancer l'impression
                    setTimeout(() => {
                        try {
                            // Accéder à la fenêtre de l'iframe
                            const iframeWindow = iframe.contentWindow;
                            
                            // Lancer l'impression
                            iframeWindow.focus();
                            iframeWindow.print();
                            
                            console.log('✅ Impression lancée');
                            
                            // Nettoyer après impression
                            setTimeout(() => {
                                document.body.removeChild(iframe);
                                console.log('🧹 Iframe supprimée');
                            }, this.autoCloseDelay);
                            
                            resolve({
                                success: true,
                                message: 'Impression lancée avec succès'
                            });
                            
                        } catch (error) {
                            console.error('❌ Erreur lors de l\'impression:', error);
                            document.body.removeChild(iframe);
                            reject(error);
                        }
                    }, 1000); // Délai de 1 seconde pour laisser charger
                };
                
                iframe.onerror = (error) => {
                    console.error('❌ Erreur chargement iframe:', error);
                    document.body.removeChild(iframe);
                    reject(new Error('Impossible de charger le ticket'));
                };
                
                // Charger le ticket
                iframe.src = ticketUrl;
                document.body.appendChild(iframe);
                
            } catch (error) {
                console.error('❌ Erreur impression directe:', error);
                reject(error);
            }
        });
    }

    /**
     * MÉTHODE 2 : Impression silencieuse (nouvelle fenêtre avec auto-close)
     * ✅ Alternative si iframe ne fonctionne pas
     */
    async printSilent(venteId) {
        return new Promise((resolve, reject) => {
            try {
                console.log(`🖨️ Impression silencieuse du ticket ${venteId}`);
                
                const ticketUrl = `/ticket/${venteId}/imprimer?auto=1`;
                
                // Ouvrir dans une petite fenêtre
                const printWindow = window.open(
                    ticketUrl,
                    'PrintTicket',
                    'width=400,height=600,menubar=no,toolbar=no,location=no,status=no'
                );
                
                if (!printWindow) {
                    throw new Error('Veuillez autoriser les popups pour ce site');
                }
                
                // Attendre le chargement
                printWindow.addEventListener('load', () => {
                    console.log('✅ Ticket chargé');
                    
                    // Lancer l'impression après un délai
                    setTimeout(() => {
                        printWindow.print();
                        
                        // Fermer après impression
                        printWindow.addEventListener('afterprint', () => {
                            setTimeout(() => {
                                printWindow.close();
                                console.log('✅ Fenêtre fermée');
                            }, 500);
                        });
                        
                        resolve({
                            success: true,
                            message: 'Impression lancée'
                        });
                    }, 1000);
                });
                
            } catch (error) {
                console.error('❌ Erreur impression silencieuse:', error);
                reject(error);
            }
        });
    }

    /**
     * MÉTHODE 3 : Impression navigateur classique (fallback)
     */
    async printBrowser(venteId) {
        try {
            console.log(`🖨️ Impression navigateur du ticket ${venteId}`);
            
            const ticketUrl = `/ticket/${venteId}/imprimer`;
            
            // Ouvrir dans un nouvel onglet
            const printTab = window.open(ticketUrl, '_blank');
            
            if (!printTab) {
                // Si bloqué, ouvrir dans le même onglet
                window.location.href = ticketUrl;
            }
            
            return {
                success: true,
                message: 'Ticket ouvert dans un nouvel onglet'
            };
            
        } catch (error) {
            console.error('❌ Erreur impression navigateur:', error);
            return {
                success: false,
                message: error.message
            };
        }
    }

    /**
     * Télécharger le ticket en PDF
     */
    async downloadPDF(venteId) {
        try {
            console.log(`📥 Téléchargement PDF du ticket ${venteId}`);
            
            const pdfUrl = `/ticket/${venteId}/pdf`;
            
            // Créer un lien temporaire
            const link = document.createElement('a');
            link.href = pdfUrl;
            link.download = `ticket-${venteId}.pdf`;
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            return {
                success: true,
                message: 'Téléchargement lancé'
            };
            
        } catch (error) {
            console.error('❌ Erreur téléchargement PDF:', error);
            return {
                success: false,
                message: error.message
            };
        }
    }

    /**
     * Configurer l'impression automatique
     */
    setAutoClose(delay) {
        this.autoCloseDelay = delay;
    }
}

// Initialisation globale
window.ticketPrinter = new TicketPrinter();

// Export pour modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TicketPrinter;
}

console.log('✅ Module TicketPrinter chargé avec succès');