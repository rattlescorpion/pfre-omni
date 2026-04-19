# Claude Skills

This directory contains custom Claude skill definitions for the PFRE-Omni workspace.

## Available skills

- `create-skill` — Generate a reusable skill definition file from a workflow, conversation, or prompt customization request.

## Skill format

Each skill is defined as a Markdown file with frontmatter and structured guidance.

Required sections:
- `name` — unique skill identifier
- `description` — short summary of what the skill does
- `args` — optional argument schema for the skill
- Skill title and content
- Workflow guidance, activation conditions, and examples

## How to use

1. Add a new Markdown file in `.claude/skills/`.
2. Use frontmatter with `name`, `description`, and optional `args`.
3. Write concise guidance for when the skill should be applied.
4. Include at least one example prompt.

## Example skill file

```md
---
name: my-skill
description: Describe what the skill does
args:
  target:
    description: "The target object"
    required: false
    type: string
---

# My Skill

Write instructions, workflow guidance, decision rules, and example prompts.
```

## Recommended conventions

- Prefer workspace-scoped skills for repository-specific tasks.
- Keep the instructions actionable and avoid vague language.
- Include completion criteria and decision branches when the workflow is non-trivial.
- Use example prompts to help the agent apply the skill correctly.

## Notes

This folder is intended for internal prompt engineering and agent customization relevant to the current repository. Skills here should support consistent, repeatable assistant behavior across the workspace.
