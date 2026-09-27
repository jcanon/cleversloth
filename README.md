# Clever Sloth LLC website

This is the complete Vue website for Clever Sloth LLC. It has no WordPress, CMS, database, or Node server requirement in production. PHP is used only for the protected contact-form endpoint.

## Update and deploy

1. Edit page content in `src/views/HomeView.vue`.
2. Edit layout, colors, typography, and responsive behavior in `src/styles.css`.
3. Edit form behavior in `src/components/ContactForm.vue` and the server endpoint in `public/api/contact.php`.
4. Run `npm run build`.
5. Upload the contents of `deploy/` to the SiteGround web root. Do not upload the outer `deploy` folder itself.

## Folder map

- `src/` — Vue pages, components, and readable site styles.
- `public/` — images, fonts, icons, backgrounds, PHP contact endpoint, and public configuration. These files are copied into `deploy/` during each build.
- `deploy/` — the current, upload-ready production website. Do not edit generated files here; rebuild from `src/` and `public/` instead.
- `scripts/check-vue-site.mjs` — the release check. Run it with `npm test`.
- `DEPLOYMENT.md` — hosting and contact-form deployment instructions.

## Local commands

- `npm install` — install development tools after a fresh download or cleanup.
- `npm run dev` — run a local editing preview.
- `npm run build` — recreate the upload-ready `deploy/` folder.
- `npm test` — run the release check.
