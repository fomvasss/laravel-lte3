import path from 'node:path';

// Adapts GitHub-flavoured pages to the site: drops the `# ` heading (Starlight renders the title
// itself) and turns relative `.md` links into site URLs (lowercase, like the page ids: fields/select2Tree.md → fields/select2tree/).
export function remarkPlainMarkdown({ root, base }) {
	return (tree, file) => {
		if (tree.children[0]?.type === 'heading' && tree.children[0].depth === 1) {
			tree.children.shift();
		}

		const visit = (node) => {
			if (node.type === 'link' && /^[^:#]+\.md(#.*)?$/.test(node.url)) {
				const [target, anchor] = node.url.split('#');
				const slug = path.relative(root, path.resolve(path.dirname(file.path), target)).replace(/(^|\/)?index\.md$|\.md$/, '').toLowerCase();
				node.url = `${base}/${slug ? `${slug}/` : ''}${anchor ? `#${anchor}` : ''}`;
			}
			node.children?.forEach(visit);
		};
		visit(tree);
	};
}
