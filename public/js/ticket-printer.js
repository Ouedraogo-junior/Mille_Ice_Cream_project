/**
 * Gestionnaire d'impression de tickets
 * Support: Impression navigateur, Bluetooth, USB
 */

class TicketPrinter {
    constructor() {
        this.printerType = this.detectPrinterType();
        this.bluetoothDevice = null;
        this.usbDevice = null;
    }

    /**
     * Détecter le type d'imprimante disponible
     */
    detectPrinterType() {
        // Vérifier le support Bluetooth
        if (navigator.bluetooth) {
            return 'bluetooth';
        }
        
        // Vérifier le support WebUSB
        if (navigator.usb) {
            return 'usb';
        }
        
        // Par défaut: impression navigateur
        return 'browser';
    }

    /**
     * Imprimer un ticket (méthode principale)
     */
    async print(venteId, method = 'auto') {
        try {
            console.log(`Impression ticket vente #${venteId} avec méthode: ${method}`);

            // Choisir la méthode d'impression
            if (method === 'auto') {
                method = this.printerType;
            }

            switch (method) {
                case 'bluetooth':
                    return await this.printBluetooth(venteId);
                    
                case 'usb':
                    return await this.printUSB(venteId);
                    
                case 'browser':
                default:
                    return await this.printBrowser(venteId);
            }
        } catch (error) {
            console.error('Erreur impression:', error);
            throw error;
        }
    }

    /**
     * Impression via le navigateur (nouvelle fenêtre)
     */
    async printBrowser(venteId) {
        const url = `/tickets/${venteId}/imprimer`;
        const width = 300;
        const height = 600;
        const left = (screen.width / 2) - (width / 2);
        const top = (screen.height / 2) - (height / 2);

        const printWindow = window.open(
            url,
            'Impression Ticket',
            `width=${width},height=${height},top=${top},left=${left},menubar=no,toolbar=no,location=no,status=no`
        );

        if (!printWindow) {
            throw new Error('Le navigateur a bloqué la fenêtre d\'impression');
        }

        // Auto-impression après chargement
        printWindow.onload = function() {
            setTimeout(() => {
                printWindow.print();
            }, 500);
        };

        return {
            success: true,
            method: 'browser',
            message: 'Ticket ouvert dans une nouvelle fenêtre'
        };
    }

    /**
     * Impression via Bluetooth (imprimante thermique)
     */
    async printBluetooth(venteId) {
        try {
            // Se connecter à l'imprimante Bluetooth si pas déjà connecté
            if (!this.bluetoothDevice) {
                await this.connectBluetooth();
            }

            // Récupérer les données ESC/POS depuis le serveur
            const response = await fetch(`/tickets/${venteId}/escpos`);
            const data = await response.json();

            if (!data.success) {
                throw new Error(data.message);
            }

            // Décoder les données base64
            const commands = this.base64ToArrayBuffer(data.data);

            // Envoyer à l'imprimante
            const characteristic = await this.getBluetoothCharacteristic();
            await characteristic.writeValue(commands);

            return {
                success: true,
                method: 'bluetooth',
                message: 'Ticket envoyé à l\'imprimante Bluetooth'
            };
        } catch (error) {
            console.error('Erreur Bluetooth:', error);
            
            // Fallback vers impression navigateur
            console.log('Fallback vers impression navigateur');
            return await this.printBrowser(venteId);
        }
    }

    /**
     * Connexion à une imprimante Bluetooth
     */
    async connectBluetooth() {
        try {
            console.log('Recherche d\'imprimante Bluetooth...');
            
            // Demander à l'utilisateur de sélectionner une imprimante
            this.bluetoothDevice = await navigator.bluetooth.requestDevice({
                filters: [
                    { services: ['000018f0-0000-1000-8000-00805f9b34fb'] }, // Service d'impression standard
                    { namePrefix: 'Printer' },
                    { namePrefix: 'POS' },
                    { namePrefix: 'BlueBamboo' },
                ],
                optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
            });

            console.log('Imprimante sélectionnée:', this.bluetoothDevice.name);

            // Se connecter au GATT Server
            const server = await this.bluetoothDevice.gatt.connect();
            console.log('Connecté au serveur GATT');

            return {
                success: true,
                device: this.bluetoothDevice.name
            };
        } catch (error) {
            console.error('Erreur connexion Bluetooth:', error);
            throw new Error('Impossible de se connecter à l\'imprimante Bluetooth');
        }
    }

    /**
     * Obtenir la caractéristique Bluetooth pour l'écriture
     */
    async getBluetoothCharacteristic() {
        if (!this.bluetoothDevice || !this.bluetoothDevice.gatt.connected) {
            await this.connectBluetooth();
        }

        const server = this.bluetoothDevice.gatt;
        const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
        const characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');

        return characteristic;
    }

    /**
     * Impression via USB (imprimante thermique)
     */
    async printUSB(venteId) {
        try {
            console.log('Impression USB pas encore implémentée');
            // TODO: Implémenter l'impression USB avec WebUSB API
            
            // Fallback
            return await this.printBrowser(venteId);
        } catch (error) {
            console.error('Erreur USB:', error);
            return await this.printBrowser(venteId);
        }
    }

    /**
     * Télécharger le ticket en PDF
     */
    async downloadPDF(venteId) {
        const url = `/tickets/${venteId}/pdf`;
        window.open(url, '_blank');
        
        return {
            success: true,
            method: 'pdf',
            message: 'PDF téléchargé'
        };
    }

    /**
     * Convertir base64 en ArrayBuffer
     */
    base64ToArrayBuffer(base64) {
        const binaryString = window.atob(base64);
        const len = binaryString.length;
        const bytes = new Uint8Array(len);
        
        for (let i = 0; i < len; i++) {
            bytes[i] = binaryString.charCodeAt(i);
        }
        
        return bytes.buffer;
    }

    /**
     * Déconnecter l'imprimante Bluetooth
     */
    disconnect() {
        if (this.bluetoothDevice && this.bluetoothDevice.gatt.connected) {
            this.bluetoothDevice.gatt.disconnect();
            console.log('Déconnecté de l\'imprimante Bluetooth');
        }
    }

    /**
     * Vérifier si une imprimante est connectée
     */
    isConnected() {
        if (this.bluetoothDevice) {
            return this.bluetoothDevice.gatt.connected;
        }
        return false;
    }

    /**
     * Obtenir les informations sur l'imprimante connectée
     */
    getPrinterInfo() {
        if (this.bluetoothDevice) {
            return {
                name: this.bluetoothDevice.name,
                id: this.bluetoothDevice.id,
                connected: this.bluetoothDevice.gatt.connected
            };
        }
        return null;
    }
}

// Instance globale
window.ticketPrinter = new TicketPrinter();

// Export pour utilisation dans les modules
if (typeof module !== 'undefined' && module.exports) {
    module.exports = TicketPrinter;
}