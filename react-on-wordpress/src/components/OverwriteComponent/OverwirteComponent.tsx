import styled from 'styled-components'
import {Col, Row} from 'reactstrap'

export const AppRow = styled(Row)`
  display: grid !important;
  grid-template-columns: 1fr 1fr 1fr;
  grid-column-gap: 10px;
  grid-row-gap: 10px;
  margin-left: 0 !important;
  margin-right: 0 !important;
`
export const AppCol = styled(Col)`
  margin: 10px auto;
  display: grid;
  grid-template-columns: 1fr 1fr;
  grid-column-gap: 10px;
`
