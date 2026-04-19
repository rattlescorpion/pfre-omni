---
name: create-skill
description: Create a reusable Claude skill definition file from a workflow, conversation, or prompt customization request.
args:
  skill_name:
    description: "The name of the skill to generate (optional)."
    required: false
    type: string
  scope:
    description: "Desired scope for the skill: workspace or personal."
    required: false
    type: string
    default: "workspace"
---

# Create Skill

This skill helps the agent convert a request or conversation into a clear, reusable skill definition. It is designed for workspace-scoped prompt customization and follows Claude skill authoring principles.

---

## 📌 When to use this skill

Activate this skill when the user asks the agent to:
- create or draft a `SKILL.md` file
- define a reusable workflow for an agent
- extract steps, decision points, and completion criteria from a conversation
- turn a multi-step process into a prompt skill

---

## 🚀 What this skill produces

- a concise skill title and purpose
- activation triggers and expected context
- reusable step-by-step workflow guidance
- decision points for ambiguity
- quality criteria and completion checks
- example prompt templates

---

## 🧭 Workflow

1. Review the full conversation and workspace context.
2. Decide the skill type:
   - `workflow template` if a repeatable process is present
   - `one-time instruction` if the request is ad hoc and specific to the current repository
3. Extract the key elements:
   - desired outcome or artifact
   - inputs and outputs
   - explicit or implicit decision branches
   - quality and completion requirements
4. Draft the skill structure:
   - title and summary
   - activation conditions
   - workflow steps
   - decision points
   - completion criteria
5. If the workflow is unclear, ask for clarification:
   - what outcome should this skill produce?
   - should it be workspace-scoped or personal?
   - should it be a quick checklist or a full multi-step workflow?
6. Save the file in the appropriate location, such as `.claude/skills/` or workspace root.

---

## ⚖️ Decision points

- If a clear workflow exists, encode it directly as step-by-step instructions.
- If the request is vague, request clarification before finalizing the skill.
- Prefer workspace conventions when local skill structure is already present.
- Keep the guidance actionable and avoid overly broad generic language.

---

## ✅ Completion criteria

- The skill has a clear name and description.
- It includes activation triggers and scope.
- It contains a reproducible workflow.
- It documents decision points and quality checks.
- It includes example prompts for using the skill.

---

## 💡 Example prompts

- "Create a `SKILL.md` for converting a conversation into a reusable agent skill."
- "Generate a skill definition for writing prompt templates and decision branches."
- "Define a workspace-scoped skill that turns a multi-step workflow into prompt guidance."

---

## 🔧 Next customization ideas

- Add a version that targets `.claude/skills/` with engine metadata or args validation.
- Extend the skill to automatically validate local skill file conventions.
- Add a checklist for workspace-specific prompt rules and naming conventions.
