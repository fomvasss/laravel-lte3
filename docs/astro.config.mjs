import { fileURLToPath } from 'node:url';
import { defineConfig, passthroughImageService } from 'astro/config';
import starlight from '@astrojs/starlight';
import starlightGitHubAlerts from 'starlight-github-alerts';
import starlightLinksValidator from 'starlight-links-validator';
import { remarkPlainMarkdown } from './src/remark-plain-markdown.mjs';

const base = '/laravel-lte3';

export default defineConfig({
	site: 'https://fomvasss.github.io',
	base,
	// keeps animated GIFs as is instead of converting them to a static WebP
	image: { service: passthroughImageService() },
	markdown: {
		remarkPlugins: [[remarkPlainMarkdown, { root: fileURLToPath(new URL('.', import.meta.url)), base }]],
	},
	integrations: [
		starlight({
			title: 'Laravel LTE3',
			description: 'AdminLTE 3 admin panel and Blade form builder for Laravel: layout, alerts, about forty form fields with AJAX, file managers and Media Library',
			social: [{ icon: 'github', label: 'GitHub', href: 'https://github.com/fomvasss/laravel-lte3' }],
			// the pages live in docs/ itself, not in src/content/docs/
			markdown: { processedDirs: ['.'] },
			expressiveCode: { shiki: { langAlias: { env: 'dotenv' } } },
			editLink: { baseUrl: 'https://github.com/fomvasss/laravel-lte3/edit/master/docs/' },
			plugins: [starlightGitHubAlerts(), starlightLinksValidator()],
			sidebar: [
				{ label: 'Getting started', items: [{ label: 'Overview', slug: 'index' }, 'installation', 'configuration'] },
				{
					label: 'Usage',
					items: [
						'usage/layout',
						'usage/forms',
						'usage/request-options',
						'usage/actions',
						'usage/dynamic-content',
						'usage/pattern-validation',
						'usage/mb-blocks',
						'usage/editors',
						'usage/media-thumbnails',
					],
				},
				{
					label: 'Fields',
					items: [
						'fields/overview',
						{ label: 'text, number, email, url, search, password, secret', slug: 'fields/text' },
						{ label: 'textarea', slug: 'fields/textarea' },
						{ label: 'slug', slug: 'fields/slug' },
						{ label: 'hidden', slug: 'fields/hidden' },
						{ label: 'colorpicker', slug: 'fields/colorpicker' },
						{ label: 'range', slug: 'fields/range' },
						{ label: 'checkbox', slug: 'fields/checkbox' },
						{ label: 'checkboxes', slug: 'fields/checkboxes' },
						{ label: 'radiogroup', slug: 'fields/radiogroup' },
						{ label: 'select2', slug: 'fields/select2' },
						{ label: 'select2Tree', slug: 'fields/select2tree' },
						{ label: 'treeview', slug: 'fields/treeview' },
						{ label: 'nestedset', slug: 'fields/nestedset' },
						{ label: 'datepicker, timepicker, datetimepicker, multidatespicker', slug: 'fields/datetime' },
						{ label: 'file', slug: 'fields/file' },
						{ label: 'fileForm', slug: 'fields/fileform' },
						{ label: 'lfmFile, lfmImage', slug: 'fields/lfmfile' },
						{ label: 'mediaFile, mediaImage', slug: 'fields/mediafile' },
						{ label: 'links', slug: 'fields/links' },
						{ label: 'lists', slug: 'fields/lists' },
						{ label: 'tableOptions', slug: 'fields/tableoptions' },
						{ label: 'xEditable', slug: 'fields/xeditable' },
						{ label: 'form', slug: 'fields/form' },
						{ label: 'link, btnSubmit, btnReset, btnModalClose', slug: 'fields/buttons' },
					],
				},
				{
					label: 'Reference',
					items: ['reference/facade', 'reference/helpers', 'reference/javascript', 'reference/views', 'reference/commands'],
				},
				'upgrading',
			],
		}),
	],
});
