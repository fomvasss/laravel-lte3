import { readFileSync } from 'node:fs';
import { defineCollection } from 'astro:content';
import { glob } from 'astro/loaders';
import { docsSchema } from '@astrojs/starlight/schema';

// The pages are plain Markdown readable on GitHub: no frontmatter, the title is the first `# ` heading.
const markdown = glob({ pattern: ['*.md', 'usage/**/*.md', 'fields/**/*.md', 'reference/**/*.md'], base: '.' });

export const collections = {
	docs: defineCollection({
		loader: {
			name: 'docs-markdown',
			load: (context) => markdown.load({
				...context,
				parseData: ({ data, filePath, ...rest }) => context.parseData({
					...rest,
					filePath,
					data: { title: readFileSync(filePath!, 'utf8').match(/^# (.+)$/m)?.[1], ...data },
				}),
			}),
		},
		schema: docsSchema(),
	}),
};
