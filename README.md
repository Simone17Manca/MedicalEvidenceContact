# MedicalEvidenceContact

Portale Laravel per mettere in contatto business sanitari e professionisti.

## Funzionalita principali

- Autenticazione Laravel Jetstream per business, professionisti e admin.
- Registrazione differenziata per profilo business e professionista.
- Gestione annunci di lavoro con CRUD e filtri di ricerca.
- Candidature professionista agli annunci.
- Dashboard professionista con documenti, candidature, esperienze e formazione.
- Area business con annunci, Point of Contact e profili candidati senza contatti personali.
- Prima interfaccia frontend per gestione colloqui e slot.
- Area admin per gestione utenti e annunci.

## Ambiente locale

Il progetto e stato configurato per Laragon in:

```text
C:\laragon\www\MedicalEvidenceContact
```

Wrapper utili su Windows:

```powershell
cmd /c composer-local.cmd install
cmd /c artisan-local.cmd migrate
cmd /c artisan-local.cmd test
npm install
npm run build
```

## Documentazione

La documentazione di progetto e raccolta nella cartella `docs`, inclusi PRD/PDR e avanzamento lavori.
