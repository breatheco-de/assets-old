import React, { useState } from "react";
import qs from "query-string";
import { Notifier, Notify } from "bc-react-notifier";

const host = "https://api.breatheco.de";

const ModalComponent = properties => {
	const [comments, setComments] = useState("");
	return (
		<div className="confirm-status-change text-center">
			<h3>Any comments for the student?</h3>
			<textarea className="form-control" onChange={e => setComments(e.target.value)}>
				{comments}
			</textarea>
			<p className="text-center">
				<button className="btn btn-success" onClick={() => properties.onConfirm({ comments, revision_status: "approved" })}>
					Approve
				</button>
				<button className="btn btn-danger" onClick={() => properties.onConfirm({ comments, revision_status: "rejected" })}>
					Reject
				</button>
			</p>
		</div>
	);
};

//create your first component
export class Home extends React.Component {
	constructor(props) {
		super(props);
		this.state = {
			assignments: [],
			catalogs: null,
			cohort: null,
			student: null,
			teacher: null,
			filters: {
				status: null,
				revision_status: "pending",
				student: null,
				assignment: null
			}
		};
	}
	componentDidMount() {
		const parsed = Object.assign(
			{
				cohort: null,
				student: null,
				teacher: null,
				access_token: null
			},
			qs.parse(window.location.search)
		);
		this.setState(parsed);
		this.updateAssigntments(parsed);
	}
	updateAssigntments(params) {
		let url = "";
		if (params.student) url = `${host}/task/?student=${params.student}`;
		else if (params.cohort) url = `${host}/task/?cohort=${params.cohort}`;
		else if (params.teacher) url = `${host}/task/?teacher=${params.teacher}`;
		else url = `${host}/task/?`;

		fetch(`${url}&access_token=${params.access_token}`, {
			cache: "no-cache"
		})
			.then(resp => resp.json())
			.then(resp => {
				const assignments = resp.data.filter(t => t.type == "assignment");
				this.setState({ assignments });

				const catalogs = {
					associated_slugs: [],
					students: [],
					student_ids: [],
					status: ["pending", "done"],
					revision_status: ["pending", "approved", "rejected"]
				};
				let atLeastOneDevlivered = false;
				const projectsWithDupicates = assignments.forEach(a => {
					if (!catalogs.associated_slugs.includes(a.associated_slug)) catalogs.associated_slugs.push(a.associated_slug);
					if (!catalogs.student_ids.includes(a.student_user_id)) {
						catalogs.students.push(a.student);
						catalogs.student_ids.push(a.student_user_id);
					}
					if (a.status == "done" && a.revision_status == "pending") atLeastOneDevlivered = true;
				});
				this.setState({ catalogs, filters: Object.assign(this.state.filters, { status: atLeastOneDevlivered ? "done" : null }) });
			})
			.catch(e => console.error(e));
	}
	render() {
		const badgeColor = status => {
			switch (status) {
				case null:
					return "badge-danger";
				case "pending":
					return "badge-danger";
				case "rejected":
					return "badge-light text-danger";
				case "approved":
					return "badge-light text-success";
				case "done":
					return "badge-light text-success";
				default:
					return "badge-light";
			}
		};
		if (!this.state.access_token) return <div className="alert alert-danger">Unable to authorize the use of this app</div>;
		return (
			<div>
				<Notifier />
				<div className="text-center mt-5 container">
					<h2>
						Student Assignments
					</h2>
					{this.state.catalogs && (
						<div className="row mb-2">
							<div className="col">
								<select
									multiple={false}
									className="form-control"
									onChange={e =>
										this.setState({
											filters: Object.assign(this.state.filters, { associated_slug: e.target.value })
										})
									}>
									<option value={""}>Filter by project</option>
									{this.state.catalogs.associated_slugs.map((a, i) => (
										<option key={i} value={a}>
											{a}
										</option>
									))}
								</select>
							</div>
							<div className="col">
								<select
									multiple={false}
									className="form-control"
									onChange={e =>
										this.setState({
											filters: Object.assign(this.state.filters, { student: e.target.value })
										})
									}>
									<option value={""}>Filter by student</option>
									{this.state.catalogs.students.map((s, i) => (
										<option key={i} value={s.id}>
											{s.first_name} {s.last_name}
										</option>
									))}
								</select>
							</div>
							<div className="col">
								<select
									multiple={false}
									className="form-control"
									value={this.state.filters.revision_status}
									onChange={e =>
										this.setState({
											filters: Object.assign(this.state.filters, { revision_status: e.target.value })
										})
									}>
									<option value={""}>Filter by teacher status</option>
									<option value={"pending"}>Pending Revision</option>
									<option value={"approved"}>Approved</option>
									<option value={"rejected"}>Rejected</option>
								</select>
							</div>
							<div className="col">
								<select
									multiple={false}
									className="form-control"
									value={this.state.filters.status}
									onChange={e =>
										this.setState({
											filters: Object.assign(this.state.filters, { status: e.target.value })
										})
									}>
									<option value={""}>Filter by student status</option>
									<option value={"pending"}>Not Delivered (pending)</option>
									<option value={"done"}>Delivered (done)</option>
								</select>
							</div>
						</div>
					)}
					<table className="table text-left">
						<thead>
							<tr>
								<th scope="col">Delivered?</th>
								<th scope="col">Reviewed?</th>
								<th scope="col">Student</th>
								<th scope="col">Title</th>
								<th scope="col">Links</th>
								<th scope="col">Actions</th>
							</tr>
						</thead>
						<tbody>
							{this.state.assignments
								.filter(a => {
									if (
										this.state.filters.student &&
										this.state.filters.student != "" &&
										a.student_user_id != this.state.filters.student
									)
										return false;
									if (
										this.state.filters.status &&
										this.state.filters.status != "" &&
										a.status != this.state.filters.status
									)
										return false;
									if (
										this.state.filters.revision_status &&
										this.state.filters.revision_status != "" &&
										a.revision_status != this.state.filters.revision_status
									)
										return false;
									if (
										this.state.filters.associated_slug &&
										this.state.filters.associated_slug != "" &&
										a.associated_slug != this.state.filters.associated_slug
									)
										return false;
									return true;
								})
								.map((a, i) => (
									<tr key={i}>
										<td>
											<span className={`badge ${badgeColor(a.status)}`}>{a.status == "done" ? "Yes" : "No"}</span>
										</td>
										<td>
											<span className={`badge ${badgeColor(a.revision_status)}`}>
												{a.revision_status ? a.revision_status : "pending"}
											</span>
										</td>
										<td>{a.student ? a.student.first_name + " " + a.student.last_name : "Loading..."}</td>
										<td>
											<a
												rel="noopener noreferrer"
												href={`https://projects.breatheco.de/project/${a.associated_slug}`}
												target="_blank">
												{a.title}
											</a>
										</td>
										<td>
											{a.github_url && (
												<button className="btn btn-primary btn-sm" onClick={() => window.open(a.github_url)}>
													Github
												</button>
											)}
											{a.live_url && (
												<button className="btn btn-primary btn-sm" onClick={() => window.open(a.live_url)}>
													Live
												</button>
											)}
										</td>
										<td>
											<select
												className="form-control"
												value={a.revision_status}
												onChange={e => {
													let noti = Notify.add(
														"info",
														ModalComponent,
														answer => {
															fetch(host + "/teachers/assignment/" + a.id, {
																method: "PUT",
																headers: {
																	"Content-Type": "application/json",
																	Authorization: `Bearer ${this.state.access_token}`
																},
																body: JSON.stringify(
																	Object.assign(a, {
																		revision_status: answer.revision_status,
																		comments: answer.description
																	})
																)
															})
																.then(resp => resp.json())
																.then(data => {
																	if (data.code == 200) {
																		Notify.success("The task was successfully updated");
																		this.setState({
																			assignments: this.state.assignments.map(a => {
																				if (a.id == data.data.id)
																					a.revision_status = data.data.revision_status;
																				return a;
																			})
																		});
																	} else Notify.error(data.msg || data);
																})
																.catch(err => Notify.error(err.msg || err));
															noti.remove();
														},
														9999999999999
													);
												}}>
												<option value={null}>Mark as...</option>
												<option value={"approved"}>Approved</option>
												<option value={"rejected"}>Rejected</option>
											</select>
										</td>
									</tr>
								))}
						</tbody>
					</table>
				</div>
			</div>
		);
	}
}
