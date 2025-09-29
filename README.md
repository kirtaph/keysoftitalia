# Key Soft Italia - Sito Web Aziendale

## 🎯 Obiettivi del Progetto
Creazione di un sito web professionale per Key Soft Italia, azienda leader a Ginosa per riparazioni tecnologiche, vendita ricondizionati e assistenza informatica dal 2008.

## 🚀 Funzionalità Completate

### ✅ Pagine Principali
- **Home** (index.php) - Homepage con hero, servizi, prodotti in evidenza
- **Chi Siamo** (chi-siamo.php) - Storia aziendale, team, valori e missione
- **Servizi** (servizi.php) - Panoramica completa dei servizi offerti
- **Ricondizionati** (ricondizionati.php) - Catalogo prodotti ricondizionati con filtri
- **Video** (video.php) - Tutorial, guide e recensioni video
- **Contatti** (contatti.php) - Form di contatto, mappa e informazioni
- **Assistenza** (assistenza.php) - Richiesta assistenza tecnica online
- **Preventivo** (preventivo.php) - Form per richiesta preventivi personalizzati
- **Privacy Policy** (privacy.php) - Informativa privacy GDPR compliant

### ✅ Sottopagine Servizi
- **Riparazioni** (servizi/riparazioni.php)
- **Vendita** (servizi/vendita.php) 
- **Sviluppo Software** (servizi/sviluppo.php)

### ✅ Configurazione Sistema
- **config/config.php** - Configurazione centrale con costanti aziendali
- **assets/php/functions.php** - Funzioni helper per URL, asset e WhatsApp
- **includes/header.php** - Header responsive con menu mobile
- **includes/footer.php** - Footer con links e informazioni

### ✅ Features Implementate
- ✨ **Auto-detection BASE_URL** per supporto sottocartelle XAMPP
- 📱 **Design Responsive** mobile-first con Bootstrap 5.3
- 🔍 **SEO Optimized** con meta tags dinamici
- 📞 **WhatsApp Integration** con messaggi precompilati e UTM tracking
- 🛡️ **CSRF Protection** per tutti i form
- 🎨 **CSS Modulare** con variabili e componenti riutilizzabili
- 📊 **Database Ready** per gestione prodotti ricondizionati

## 📂 Struttura File Corrente

```
key-soft-italia/
├── index.php                 # Homepage
├── chi-siamo.php            # Chi Siamo
├── servizi.php              # Servizi
├── ricondizionati.php       # Prodotti Ricondizionati
├── video.php                # Video e Tutorial
├── contatti.php             # Contatti
├── assistenza.php           # Assistenza Tecnica
├── preventivo.php           # Richiesta Preventivo
├── privacy.php              # Privacy Policy
│
├── config/
│   └── config.php           # Configurazione centrale
│
├── assets/
│   ├── css/
│   │   ├── variables.css   # Variabili CSS globali
│   │   ├── main.css        # Stili principali
│   │   ├── components.css  # Componenti riutilizzabili
│   │   └── pages/          # Stili specifici per pagina
│   ├── js/
│   │   └── main.js         # JavaScript principale
│   └── php/
│       └── functions.php   # Funzioni helper PHP
│
├── includes/
│   ├── header.php          # Header del sito
│   └── footer.php          # Footer del sito
│
├── servizi/
│   ├── riparazioni.php     # Servizio riparazioni
│   ├── vendita.php         # Servizio vendita
│   └── sviluppo.php        # Sviluppo software
│
└── process/
    └── (vuoto)             # Per futuri script di processamento form
```

## 🔧 Problemi Risolti

### ✅ Risoluzione Pagine Nere
- **Problema**: Le pagine assistenza.php, preventivo.php, video.php, privacy.php mostravano schermo nero
- **Causa**: Utilizzo errato di includes/head.php con percorsi CSS sbagliati
- **Soluzione**: Riscritte tutte le pagine usando la struttura di chi-siamo.php con inclusioni dirette dei CSS

### ✅ Fix Menu Mobile
- **Problema**: Menu mobile non funzionante
- **Soluzione**: Aggiunto JavaScript e CSS per offcanvas Bootstrap

### ✅ Database Constants
- **Problema**: Ridefinizione costanti database causava warning
- **Soluzione**: Aggiunti controlli condizionali con defined()

## 🎯 URI Funzionali

### Pagine Principali
- `/` o `/index.php` - Homepage
- `/chi-siamo.php` - Informazioni aziendali
- `/servizi.php` - Lista servizi
- `/ricondizionati.php` - Catalogo prodotti
- `/video.php` - Video tutorial
- `/contatti.php` - Form contatto
- `/assistenza.php` - Richiesta assistenza
- `/preventivo.php` - Richiesta preventivo
- `/privacy.php` - Privacy policy

### Sottopagine Servizi
- `/servizi/riparazioni.php` - Dettaglio riparazioni
- `/servizi/vendita.php` - Dettaglio vendita
- `/servizi/sviluppo.php` - Sviluppo software

### API WhatsApp (via helper function)
- `whatsapp_link($message, $utm_source, $utm_medium, $utm_campaign)`

## 📝 TODO - Funzionalità da Implementare

### 🔴 Priorità Alta
1. **Process Scripts**
   - [ ] process/contatti.php - Gestione form contatti
   - [ ] process/assistenza.php - Gestione richieste assistenza
   - [ ] process/preventivo.php - Gestione preventivi
   - [ ] PHPMailer configuration

2. **Database**
   - [ ] Schema database prodotti ricondizionati
   - [ ] Sistema gestione leads
   - [ ] Tracking richieste assistenza

3. **Admin Panel**
   - [ ] Login amministratore
   - [ ] CRUD prodotti ricondizionati
   - [ ] Gestione richieste/preventivi
   - [ ] Dashboard statistiche

### 🟡 Priorità Media
4. **Pagine Aggiuntive**
   - [ ] cookie-policy.php
   - [ ] termini-servizio.php
   - [ ] 404.php - Pagina errore personalizzata
   - [ ] sitemap.xml

5. **Ottimizzazioni**
   - [ ] Lazy loading immagini
   - [ ] Minificazione CSS/JS
   - [ ] Cache headers
   - [ ] Compressione gzip

### 🟢 Priorità Bassa
6. **Features Extra**
   - [ ] Blog/News section
   - [ ] Area clienti con login
   - [ ] Sistema recensioni
   - [ ] Live chat widget

## 🛠️ Tecnologie Utilizzate
- **Backend**: PHP 8.0+ (vanilla)
- **Frontend**: HTML5, CSS3, JavaScript ES6
- **Framework CSS**: Bootstrap 5.3.2
- **Icons**: RemixIcon 3.5.0
- **Fonts**: Google Fonts (Inter, Poppins)
- **Database**: MySQL (predisposto)
- **Server**: XAMPP compatible

## 📞 Contatti Sviluppo
Per informazioni tecniche o modifiche al sito, contattare il team di sviluppo.

## 🔐 Sicurezza
- CSRF Protection implementata su tutti i form
- Prepared statements per query database
- Input validation e sanitization
- HTTPS ready
- Headers di sicurezza configurati

## 📈 Next Steps Consigliati

1. **Immediati**:
   - Configurare database MySQL
   - Implementare script process/ per gestione form
   - Configurare PHPMailer per invio email

2. **Breve termine**:
   - Creare admin panel base
   - Popolare database prodotti ricondizionati
   - Aggiungere immagini reali prodotti/servizi

3. **Medio termine**:
   - Implementare sistema recensioni clienti
   - Aggiungere blog aziendale
   - Ottimizzare performance (cache, CDN)

## 🌐 URL Produzione
- **Sito Web**: (da configurare)
- **API Endpoint**: /api/ (da implementare)

## 📊 Modelli Dati

### Prodotti Ricondizionati
```sql
products (
  id INT PRIMARY KEY,
  name VARCHAR(255),
  category VARCHAR(100),
  brand VARCHAR(100),
  condition VARCHAR(50),
  price DECIMAL(10,2),
  original_price DECIMAL(10,2),
  description TEXT,
  features TEXT,
  warranty INT,
  stock INT,
  images JSON,
  created_at TIMESTAMP,
  updated_at TIMESTAMP
)
```

### Richieste Assistenza
```sql
assistance_requests (
  id INT PRIMARY KEY,
  name VARCHAR(255),
  email VARCHAR(255),
  phone VARCHAR(50),
  device_type VARCHAR(100),
  problem_description TEXT,
  urgency VARCHAR(50),
  status VARCHAR(50),
  created_at TIMESTAMP
)
```

## ⚡ Performance
- Tempo caricamento target: < 3s
- Mobile score target: > 90/100
- SEO score target: > 95/100

---

**Ultimo aggiornamento**: <?php echo date('d/m/Y H:i'); ?>