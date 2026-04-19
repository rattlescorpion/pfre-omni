# Create Skill Definition

## Purpose
Create a reusable skill definition file that helps the agent turn a conversation or request into a clear, workspace-scoped skill. This skill is intended for authoring new `SKILL.md` files or similar prompt customization artifacts.

## When to use this skill
Activate this skill when the user asks the agent to:
- create or draft a `SKILL.md`
- define a reusable workflow for agent customization
- turn a multi-step conversation into a prompt skill
- extract decision points and completion checks from a workflow

## What this skill produces
- a clear skill description
- trigger conditions and activation context
- step-by-step workflow guidance
- decision branches and quality criteria
- example prompts for using the skill

## Workflow
1. Review the entire conversation history and workspace context.
2. Identify whether the request is:
   - a reusable workflow template, or
   - a one-time instruction for this repository.
3. Extract the workflow steps and decision points:
   - what outcome is expected?
   - what are the inputs and outputs?
   - when should the skill ask for clarification?
4. Draft the skill metadata and structure:
   - title and summary
   - activation triggers
   - scope (workspace-scoped vs personal)
   - quality checklist and completion criteria
5. If the workflow is ambiguous, ask the user:
   - what outcome should this skill produce?
   - should it be workspace-scoped or personal?
   - do they want a quick checklist or a full step-by-step workflow?
6. Save the file as `SKILL.md` in the workspace root or the appropriate skills directory.

## Decision points
- If a clear workflow emerges, convert it directly into step-by-step instructions.
- If not, request clarification about the desired artifact and scope.
- Prefer workspace conventions when repository-specific skill formats exist.
- Keep the skill concise and actionable.

## Completion criteria
- The file contains a clear title and purpose.
- It includes a reproducible workflow.
- It identifies activation conditions.
- It defines output expectations and quality checks.
- It suggests example prompts to exercise the skill.

## Example prompts
- "Create a `SKILL.md` for converting a conversation into an agent skill." 
- "Help me define a skill for writing prompt templates and decision branches." 
- "Generate step-by-step instructions for authoring a new workspace skill file."

## Next customization ideas
- Add a variant that targets `.claude/skills/` or other repository skill directories.
- Extend this skill to automatically validate `SKILL.md` structure against local conventions.
- Add a checklist for capturing user preferences and workspace-specific conventions.
